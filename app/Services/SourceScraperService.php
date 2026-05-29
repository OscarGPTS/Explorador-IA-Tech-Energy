<?php

namespace App\Services;

use App\Models\News;
use App\Models\Recommendation;
use App\Models\ScrapingSource;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Motor de scraping dirigido por la tabla `scraping_sources`.
 *
 * Estrategia anti-bloqueo:
 *  - RSS como mecanismo primario (estable, estructurado, no se bloquea).
 *  - Fallback genérico a HTML cuando la fuente lo requiere.
 *  - User-Agent de navegador + timeout + dedupe por external_link.
 *  - Estado de cada corrida persistido en la propia fuente (panel en vivo).
 */
class SourceScraperService
{
    private Client $client;

    /** User-Agents rotativos para reducir bloqueos. */
    private array $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15',
        'Mozilla/5.0 (X11; Linux x86_64; rv:121.0) Gecko/20100101 Firefox/121.0',
    ];

    public function __construct()
    {
        $this->client = new Client([
            'timeout'         => 25,
            'connect_timeout' => 10,
            'verify'          => false,
            'http_errors'     => false,
        ]);
    }

    /**
     * Ejecuta el scraping de todas las fuentes activas de un módulo.
     *
     * @param  string  $module  'news' | 'recommendations'
     */
    public function scrapeModule(string $module): array
    {
        $sources = ScrapingSource::query()
            ->where('module', $module)
            ->where('is_active', true)
            ->get();

        $summary = ['sources' => 0, 'success' => 0, 'errors' => 0, 'items' => 0, 'details' => []];

        foreach ($sources as $source) {
            $result = $this->scrapeSource($source);
            $summary['sources']++;
            $summary['items']   += $result['items'];
            $summary['success'] += $result['status'] === 'ok' ? 1 : 0;
            $summary['errors']  += $result['status'] === 'error' ? 1 : 0;
            $summary['details'][$source->name] = $result;
        }

        return $summary;
    }

    /**
     * Ejecuta el scraping de una sola fuente y guarda su estado.
     */
    public function scrapeSource(ScrapingSource $source): array
    {
        $items = 0;
        $status = 'ok';
        $error = null;

        try {
            $entries = $source->feed_type === 'rss'
                ? $this->fetchRss($source->url, $source->max_items)
                : $this->fetchHtml($source);

            foreach ($entries as $entry) {
                if ($this->saveEntry($source, $entry)) {
                    $items++;
                }
                // Pequeña pausa para no saturar la fuente.
                usleep(150000); // 150ms
            }
        } catch (\Throwable $e) {
            $status = 'error';
            $error = Str::limit($e->getMessage(), 480);
            Log::error("Scraping fuente #{$source->id} ({$source->name}): " . $e->getMessage());
        }

        $source->forceFill([
            'last_run_at' => Carbon::now(),
            'last_status' => $status,
            'last_items'  => $items,
            'last_error'  => $error,
        ])->save();

        return ['status' => $status, 'items' => $items, 'error' => $error];
    }

    /**
     * Descarga y parsea un feed RSS/Atom.
     */
    private function fetchRss(string $url, int $maxItems): array
    {
        $response = $this->client->get($url, ['headers' => $this->headers()]);
        $code = $response->getStatusCode();
        if ($code >= 400) {
            throw new \RuntimeException("HTTP {$code} al solicitar el feed RSS.");
        }

        $body = (string) $response->getBody();
        if (trim($body) === '') {
            throw new \RuntimeException('El feed RSS devolvió contenido vacío.');
        }

        $xml = @simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xml === false) {
            throw new \RuntimeException('No se pudo parsear el XML del feed.');
        }

        $entries = [];

        // RSS 2.0: channel > item
        if (isset($xml->channel->item)) {
            foreach ($xml->channel->item as $item) {
                $entries[] = $this->mapRssItem($item);
                if (count($entries) >= $maxItems) {
                    break;
                }
            }
        }
        // Atom: feed > entry
        elseif (isset($xml->entry)) {
            foreach ($xml->entry as $entry) {
                $link = '';
                if (isset($entry->link['href'])) {
                    $link = (string) $entry->link['href'];
                }
                $entries[] = [
                    'title'       => trim((string) $entry->title),
                    'link'        => $link,
                    'description' => trim(strip_tags((string) ($entry->summary ?? $entry->content ?? ''))),
                    'content'     => trim(strip_tags((string) ($entry->content ?? $entry->summary ?? ''))),
                    'image'       => null,
                    'source'      => parse_url($link, PHP_URL_HOST) ?: null,
                ];
                if (count($entries) >= $maxItems) {
                    break;
                }
            }
        }

        return $entries;
    }

    /**
     * Mapea un <item> de RSS 2.0 a nuestra estructura, extrayendo imagen si existe.
     */
    private function mapRssItem(\SimpleXMLElement $item): array
    {
        $link = trim((string) $item->link);
        $description = trim(strip_tags((string) $item->description));

        // Imagen: enclosure, media:content o media:thumbnail.
        $image = null;
        if (isset($item->enclosure['url'])) {
            $image = (string) $item->enclosure['url'];
        }
        $media = $item->children('media', true);
        if (!$image && isset($media->content) && isset($media->content->attributes()->url)) {
            $image = (string) $media->content->attributes()->url;
        }
        if (!$image && isset($media->thumbnail) && isset($media->thumbnail->attributes()->url)) {
            $image = (string) $media->thumbnail->attributes()->url;
        }

        // Contenido completo si viene en content:encoded.
        $contentEncoded = $item->children('content', true);
        $content = isset($contentEncoded->encoded)
            ? trim(strip_tags((string) $contentEncoded->encoded))
            : $description;

        // Fuente real: <source> (Google News la incluye) o el host del enlace.
        $sourceName = isset($item->source) && trim((string) $item->source) !== ''
            ? trim((string) $item->source)
            : (parse_url($link, PHP_URL_HOST) ?: null);

        // Google News añade " - Fuente" al final del título; lo limpiamos.
        $title = trim((string) $item->title);
        if ($sourceName && str_ends_with($title, ' - ' . $sourceName)) {
            $title = trim(substr($title, 0, -strlen(' - ' . $sourceName)));
        }

        return [
            'title'       => $title,
            'link'        => $link,
            'description' => $description,
            'content'     => $content,
            'image'       => $image,
            'source'      => $sourceName,
        ];
    }

    /**
     * Scraping HTML genérico configurable por selectores XPath.
     * selectors = { "item": "...", "title": "...", "link": "...", "summary": "...", "image": "..." }
     */
    private function fetchHtml(ScrapingSource $source): array
    {
        $response = $this->client->get($source->url, ['headers' => $this->headers()]);
        $code = $response->getStatusCode();
        if ($code >= 400) {
            throw new \RuntimeException("HTTP {$code} al solicitar la página.");
        }

        $html = (string) $response->getBody();
        if (trim($html) === '') {
            throw new \RuntimeException('La página devolvió contenido vacío.');
        }

        $selectors = $source->selectors ?: [];
        $itemQuery = $selectors['item'] ?? '//article';
        $titleQ    = $selectors['title'] ?? './/h2 | .//h3 | .//h1';
        $linkQ     = $selectors['link'] ?? './/a/@href';
        $summaryQ  = $selectors['summary'] ?? './/p';
        $imageQ    = $selectors['image'] ?? './/img/@src';

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $base = parse_url($source->url);
        $origin = isset($base['scheme'], $base['host']) ? $base['scheme'] . '://' . $base['host'] : '';

        $entries = [];
        $nodes = $xpath->query($itemQuery);
        if ($nodes === false) {
            throw new \RuntimeException('El selector "item" no es un XPath válido.');
        }

        foreach ($nodes as $node) {
            $title = $this->firstText($xpath, $titleQ, $node);
            $link  = $this->firstText($xpath, $linkQ, $node);
            if ($title === '' || $link === '') {
                continue;
            }
            if (Str::startsWith($link, '/')) {
                $link = $origin . $link;
            }

            $summary = $this->firstText($xpath, $summaryQ, $node);
            $image   = $this->firstText($xpath, $imageQ, $node);
            if ($image && Str::startsWith($image, '/')) {
                $image = $origin . $image;
            }

            $entries[] = [
                'title'       => $title,
                'link'        => $link,
                'description' => $summary,
                'content'     => $summary,
                'image'       => $image ?: null,
                'source'      => $base['host'] ?? null,
            ];

            if (count($entries) >= $source->max_items) {
                break;
            }
        }

        return $entries;
    }

    private function firstText(DOMXPath $xpath, string $query, \DOMNode $context): string
    {
        $res = $xpath->query($query, $context);
        if ($res === false || $res->length === 0) {
            return '';
        }
        return trim($res->item(0)->nodeValue ?? '');
    }

    /**
     * Ruido de empleo/ofertas que NO debe aparecer (ambos módulos).
     */
    private array $blockedCommon = [
        'vacante', 'vacantes', 'oferta de empleo', 'ofertas de empleo', 'bolsa de trabajo',
        'se busca', 'se buscan', 'contratación de personal', 'reclutamiento',
        'now hiring', 'job opening', 'job openings', 'apply now', 'we are hiring',
    ];

    /**
     * Ruido adicional solo para RECOMENDACIONES (feed de formación/eventos):
     * no queremos artículos de "cómo conseguir trabajo / trabajar en X / sueldos".
     */
    private array $blockedRecommendations = [
        'empleo', 'empleos', 'trabajo en', 'trabajar en', 'cómo conseguir', 'como conseguir',
        'cuánto gana', 'cuanto gana', 'cuánto cobra', 'sueldo', 'sueldos', 'salario', 'salarios',
        'salary', 'jobs', 'how to get a job', 'how to work', 'hiring', 'despido', 'despidos',
        'layoff', 'layoffs',
    ];

    /**
     * Determina si un item debe descartarse por su título (filtro anti-ruido).
     */
    private function isBlocked(string $title, string $module): bool
    {
        $t = mb_strtolower($title);

        foreach ($this->blockedCommon as $kw) {
            if (str_contains($t, $kw)) {
                return true;
            }
        }

        if ($module === 'recommendations') {
            foreach ($this->blockedRecommendations as $kw) {
                if (str_contains($t, $kw)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Guarda un item como News o Recommendation, evitando duplicados por external_link.
     */
    private function saveEntry(ScrapingSource $source, array $entry): bool
    {
        if (empty($entry['title']) || empty($entry['link'])) {
            return false;
        }

        // Filtro anti-ruido (ofertas de empleo, "trabajar en…", sueldos, etc.).
        if ($this->isBlocked($entry['title'], $source->module)) {
            return false;
        }

        $now = Carbon::now();

        if ($source->module === 'news') {
            if (News::where('external_link', $entry['link'])->exists()) {
                return false;
            }
            News::create([
                'title'        => Str::limit($entry['title'], 250, ''),
                'description'  => Str::limit($entry['description'] ?? '', 500, ''),
                'content'      => $entry['content'] ?? null,
                'image_url'    => $entry['image'] ?? null,
                'external_link' => $entry['link'],
                'source'       => $entry['source'] ?? $source->name,
                'news_type_id' => $source->type_id,
                'is_scraped'   => true,
                'scraped_at'   => $now,
            ]);
            return true;
        }

        // recommendations
        if (Recommendation::where('external_link', $entry['link'])->exists()) {
            return false;
        }
        Recommendation::create([
            'title'                  => Str::limit($entry['title'], 250, ''),
            'description'            => Str::limit($entry['description'] ?? '', 500, ''),
            'content'                => $entry['content'] ?? null,
            'image_url'              => $entry['image'] ?? null,
            'external_link'          => $entry['link'],
            'source'                 => $entry['source'] ?? $source->name,
            'sub_area'               => $source->sub_area,
            'recommendation_type_id' => $source->type_id,
            'is_scraped'             => true,
            'scraped_at'             => $now,
        ]);
        return true;
    }

    private function headers(): array
    {
        return [
            'User-Agent'      => $this->userAgents[array_rand($this->userAgents)],
            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language' => 'es-MX,es;q=0.9,en;q=0.8',
        ];
    }
}
