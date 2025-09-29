<?php

namespace App\Services;

use App\Models\News;
use App\Models\NewsType;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;

class NewsScrapingService
{
    private Client $client;
    private array $sources;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ]
        ]);

        $this->sources = [
            'eluniversal' => [
                'base_url' => 'https://www.eluniversal.com.mx',
                'sections' => [
                    'economia' => [
                        'url' => '/cartera',
                        'types' => ['Economía Nacional', 'Industria y Negocios']
                    ],
                    'negocios' => [
                        'url' => '/cartera/negocios',
                        'types' => ['Industria y Negocios', 'Energía y Tecnología']
                    ]
                ]
            ],
            'elfinanciero' => [
                'base_url' => 'https://www.elfinanciero.com.mx',
                'sections' => [
                    'economia' => [
                        'url' => '/economia',
                        'types' => ['Economía Nacional', 'Administración y Finanzas']
                    ],
                    'empresas' => [
                        'url' => '/empresas',
                        'types' => ['Industria y Negocios', 'Contratos']
                    ]
                ]
            ],
            'milenio' => [
                'base_url' => 'https://www.milenio.com',
                'sections' => [
                    'negocios' => [
                        'url' => '/negocios',
                        'types' => ['Industria y Negocios', 'Economía Nacional']
                    ],
                    'economia' => [
                        'url' => '/temas/economia',
                        'types' => ['Economía Nacional', 'Administración y Finanzas']
                    ]
                ]
            ]
        ];
    }

    /**
     * Obtener noticias de todas las fuentes
     */
    public function scrapeAllSources(): array
    {
        $results = [
            'success' => 0,
            'errors' => 0,
            'total' => 0,
            'details' => []
        ];

        foreach ($this->sources as $sourceName => $sourceConfig) {
            $sourceResult = $this->scrapeSource($sourceName, $sourceConfig);
            $results['success'] += $sourceResult['success'];
            $results['errors'] += $sourceResult['errors'];
            $results['total'] += $sourceResult['total'];
            $results['details'][$sourceName] = $sourceResult;
        }

        return $results;
    }

    /**
     * Obtener noticias de una fuente específica
     */
    public function scrapeSource(string $sourceName, array $sourceConfig): array
    {
        $results = [
            'success' => 0,
            'errors' => 0,
            'total' => 0,
            'sections' => []
        ];

        Log::info("Iniciando scraping de {$sourceName}");

        foreach ($sourceConfig['sections'] as $sectionName => $sectionConfig) {
            try {
                $sectionResult = $this->scrapeSection(
                    $sourceName,
                    $sectionName,
                    $sourceConfig['base_url'] . $sectionConfig['url'],
                    $sectionConfig['types']
                );

                $results['success'] += $sectionResult['success'];
                $results['errors'] += $sectionResult['errors'];
                $results['total'] += $sectionResult['total'];
                $results['sections'][$sectionName] = $sectionResult;

            } catch (\Exception $e) {
                Log::error("Error scraping {$sourceName}/{$sectionName}: " . $e->getMessage());
                $results['errors']++;
                $results['total']++;
            }
        }

        return $results;
    }

    /**
     * Obtener noticias de una sección específica
     */
    private function scrapeSection(string $source, string $section, string $url, array $newsTypes): array
    {
        $results = [
            'success' => 0,
            'errors' => 0,
            'total' => 0,
            'articles' => []
        ];

        try {
            Log::info("Scraping section: {$source}/{$section} - {$url}");

            $response = $this->client->get($url);
            $html = $response->getBody()->getContents();

            $articles = $this->extractArticles($html, $source, $url);

            foreach ($articles as $article) {
                try {
                    $saved = $this->saveArticle($article, $source, $section, $newsTypes);
                    if ($saved) {
                        $results['success']++;
                        $results['articles'][] = $article['title'];
                    } else {
                        $results['errors']++;
                    }
                    $results['total']++;
                } catch (\Exception $e) {
                    Log::error("Error saving article: " . $e->getMessage());
                    $results['errors']++;
                    $results['total']++;
                }
            }

        } catch (RequestException $e) {
            Log::error("HTTP Error scraping {$url}: " . $e->getMessage());
            throw $e;
        }

        return $results;
    }

    /**
     * Extraer artículos del HTML
     */
    private function extractArticles(string $html, string $source, string $baseUrl): array
    {
        $articles = [];

        // Limpiar HTML
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        // Selectores específicos por fuente
        $selectors = $this->getSelectors($source);

        foreach ($selectors as $selector) {
            $nodes = $xpath->query($selector['query']);

            foreach ($nodes as $node) {
                try {
                    $article = $this->extractArticleFromNode($node, $selector, $baseUrl, $xpath);
                    if ($article && $this->isValidArticle($article)) {
                        // Extraer contenido completo si es posible
                        $article['full_content'] = $this->extractFullContent($article['link'], $source);
                        $articles[] = $article;
                    }
                } catch (\Exception $e) {
                    Log::warning("Error extracting article: " . $e->getMessage());
                }
            }
        }

        return array_unique($articles, SORT_REGULAR);
    }

    /**
     * Obtener selectores CSS específicos por fuente
     */
    private function getSelectors(string $source): array
    {
        $selectors = [
            'eluniversal' => [
                [
                    'query' => '//article[contains(@class, "story")] | //div[contains(@class, "nota")]',
                    'title' => './/h2/a | .//h3/a | .//h1',
                    'link' => './/h2/a/@href | .//h3/a/@href | .//a/@href',
                    'summary' => './/p[position()<=2 and string-length(text())>50] | .//div[contains(@class, "resumen")]//p',
                    'image' => './/img[contains(@class, "img-responsive") or contains(@src, "eluniversal")]/@src | .//picture//img/@src'
                ],
                [
                    'query' => '//div[contains(@class, "story-card") or contains(@class, "col-")]//article',
                    'title' => './/h2 | .//h3 | .//h4',
                    'link' => './/a/@href',
                    'summary' => './/p[string-length(text())>30]',
                    'image' => './/img/@src | .//img/@data-src'
                ]
            ],
            'elfinanciero' => [
                [
                    'query' => '//article[contains(@class, "ArticleCard") or contains(@class, "story")]',
                    'title' => './/h2/a | .//h3/a | .//h1',
                    'link' => './/a/@href',
                    'summary' => './/p[position()<=2 and string-length(text())>50] | .//div[contains(@class, "excerpt")]//p',
                    'image' => './/img[contains(@class, "featured") or contains(@src, "elfinanciero")]/@src | .//picture//img/@src'
                ],
                [
                    'query' => '//div[contains(@class, "story") or contains(@class, "nota")]',
                    'title' => './/h2 | .//h3 | .//h4',
                    'link' => './/a/@href',
                    'summary' => './/p[string-length(text())>30]',
                    'image' => './/img/@src | .//img/@data-src'
                ]
            ],
            'milenio' => [
                [
                    'query' => '//article[contains(@class, "story") or contains(@class, "nota")]',
                    'title' => './/h2/a | .//h3/a | .//h1',
                    'link' => './/a/@href',
                    'summary' => './/p[position()<=2 and string-length(text())>50] | .//div[contains(@class, "bajada")]//p',
                    'image' => './/img[contains(@class, "img-responsive") or contains(@src, "milenio")]/@src | .//picture//img/@src'
                ],
                [
                    'query' => '//div[contains(@class, "card") or contains(@class, "col-")]//article',
                    'title' => './/h2 | .//h3 | .//h4',
                    'link' => './/a/@href',
                    'summary' => './/p[string-length(text())>30]',
                    'image' => './/img/@src | .//img/@data-src | .//img/@data-lazy'
                ]
            ]
        ];

        return $selectors[$source] ?? [
            [
                'query' => '//article | //div[contains(@class, "article")] | //div[contains(@class, "story")]',
                'title' => './/h1 | .//h2 | .//h3',
                'link' => './/a/@href',
                'summary' => './/p[position()<=2 and string-length(text())>30]',
                'image' => './/img/@src | .//img/@data-src'
            ]
        ];
    }

    /**
     * Extraer datos del artículo desde el nodo DOM
     */
    private function extractArticleFromNode($node, array $selector, string $baseUrl, DOMXPath $xpath): ?array
    {
        // Extraer título
        $titleNodes = $xpath->query($selector['title'], $node);
        $title = $titleNodes->length > 0 ? trim($titleNodes->item(0)->textContent) : null;

        // Extraer enlace
        $linkNodes = $xpath->query($selector['link'], $node);
        $link = null;
        if ($linkNodes->length > 0) {
            $link = $linkNodes->item(0)->nodeValue ?? $linkNodes->item(0)->textContent;
            $link = $this->normalizeUrl($link, $baseUrl);
        }

        // Extraer resumen
        $summaryNodes = $xpath->query($selector['summary'], $node);
        $summary = $summaryNodes->length > 0 ? trim($summaryNodes->item(0)->textContent) : null;

        // Extraer imagen
        $imageNodes = $xpath->query($selector['image'], $node);
        $image = null;
        if ($imageNodes->length > 0) {
            $image = $imageNodes->item(0)->nodeValue;
            $image = $this->normalizeUrl($image, $baseUrl);
        }

        if (!$title || !$link) {
            return null;
        }

        return [
            'title' => $title,
            'link' => $link,
            'summary' => $summary ?: substr($title, 0, 200),
            'image_url' => $image,
            'scraped_at' => Carbon::now()
        ];
    }

    /**
     * Normalizar URL (convertir relativas a absolutas)
     */
    private function normalizeUrl(string $url, string $baseUrl): string
    {
        if (empty($url)) {
            return '';
        }

        // Si ya es una URL completa
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        // Si comienza con //
        if (str_starts_with($url, '//')) {
            return 'https:' . $url;
        }

        // Si es una URL relativa
        if (str_starts_with($url, '/')) {
            $parsedBase = parse_url($baseUrl);
            return $parsedBase['scheme'] . '://' . $parsedBase['host'] . $url;
        }

        return $baseUrl . '/' . ltrim($url, '/');
    }

    /**
     * Validar si el artículo es válido
     */
    private function isValidArticle(array $article): bool
    {
        // Verificaciones básicas
        if (empty($article['title']) || empty($article['link'])) {
            return false;
        }

        // Título debe tener longitud adecuada
        if (strlen($article['title']) < 20 || strlen($article['title']) > 200) {
            return false;
        }

        // Contenido mínimo
        if (strlen($article['summary']) < 30) {
            return false;
        }

        // Verificar que no sea contenido excluido
        if ($this->isExcludedContent($article['title'])) {
            return false;
        }

        // Verificar que sea un enlace válido
        if (!filter_var($article['link'], FILTER_VALIDATE_URL)) {
            return false;
        }

        return true;
    }

    /**
     * Verificar si el contenido debe ser excluido
     */
    private function isExcludedContent(string $title): bool
    {
        $excludePatterns = [
            'publicidad',
            'suscríbete',
            'newsletter',
            'redes sociales',
            'síguenos',
            'más noticias',
            'últimas noticias',
            'video',
            'en vivo',
            'transmisión',
            'galería',
            'fotos',
            'compartir',
            'comentarios',
            'opinión',
            'editorial',
            'columna',
            'blog',
            'podcast',
            'radio',
            'memes',
            'viral',
            'trending',
            'especial',
            'suplemento'
        ];

        $titleLower = strtolower($title);
        
        // Verificar patrones de exclusión
        foreach ($excludePatterns as $pattern) {
            if (str_contains($titleLower, $pattern)) {
                return true;
            }
        }

        // Verificar si el título es muy corto o muy largo
        if (strlen($title) < 20 || strlen($title) > 200) {
            return true;
        }

        // Verificar si contiene muchos números (probable que sea publicidad)
        if (preg_match('/\d+/', $title) && strlen(preg_replace('/\D/', '', $title)) > 4) {
            return true;
        }

        return false;
    }

    /**
     * Guardar artículo en la base de datos
     */
    private function saveArticle(array $article, string $source, string $section, array $newsTypes): bool
    {
        try {
            // Verificar duplicados por título y link (menos estricto)
            $existingNews = News::where('external_link', $article['link'])
                ->first();
                
            if ($existingNews) {
                Log::debug("Article already exists: " . $article['title']);
                return false;
            }

            // Verificar duplicados por título similar (solo primeras 80 caracteres)
            $titlePrefix = substr($article['title'], 0, 80);
            $similarNews = News::where('title', 'LIKE', $titlePrefix . '%')->first();
            if ($similarNews) {
                Log::debug("Similar article exists: " . $article['title']);
                return false;
            }

            // Verificar que el artículo tenga contenido suficiente
            if (strlen($article['title']) < 20 || strlen($article['summary']) < 50) {
                Log::debug("Article content too short: " . $article['title']);
                return false;
            }

            // Categorizar inteligentemente basado en contenido
            $newsType = $this->categorizeNews($article['title'], $article['summary'], $newsTypes);
            if (!$newsType) {
                Log::warning("No suitable news type found for: " . $article['title']);
                return false;
            }

            // Descargar imagen si está disponible
            $localImagePath = null;
            if (!empty($article['image_url'])) {
                $localImagePath = $this->downloadAndSaveImage($article['image_url'], $article['title']);
            }

            // Crear la noticia
            News::create([
                'title' => substr($article['title'], 0, 255),
                'description' => $article['summary'], // Resumen para listing
                'content' => $article['full_content'] ?? $article['summary'], // Contenido completo
                'external_link' => $article['link'],
                'image_url' => $article['image_url'],
                'image' => $localImagePath, // Imagen local descargada
                'source' => $source,
                'news_type_id' => $newsType->id,
                'is_scraped' => true,
                'scraped_at' => $article['scraped_at'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            Log::info("Saved article: " . $article['title'] . " -> Category: " . $newsType->name);
            return true;

        } catch (\Exception $e) {
            Log::error("Error saving article '{$article['title']}': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener estadísticas del scraping
     */
    public function getScrapingStats(): array
    {
        return [
            'total_scraped_news' => News::where('is_scraped', true)->count(),
            'today_scraped' => News::where('is_scraped', true)
                ->whereDate('scraped_at', Carbon::today())
                ->count(),
            'by_source' => News::where('is_scraped', true)
                ->selectRaw('source, COUNT(*) as count')
                ->groupBy('source')
                ->pluck('count', 'source')
                ->toArray(),
            'by_type' => News::where('is_scraped', true)
                ->join('news_type', 'news.news_type_id', '=', 'news_type.id')
                ->selectRaw('news_type.name, COUNT(*) as count')
                ->groupBy('news_type.name')
                ->pluck('count', 'name')
                ->toArray(),
            'last_scraping' => News::where('is_scraped', true)
                ->orderBy('scraped_at', 'desc')
                ->first()?->scraped_at
        ];
    }

    /**
     * Limpiar noticias antiguas
     */
    public function cleanOldNews(int $daysOld = 30): int
    {
        $cutoffDate = Carbon::now()->subDays($daysOld);
        
        return News::where('is_scraped', true)
            ->where('created_at', '<', $cutoffDate)
            ->delete();
    }

    /**
     * Categorizar noticias inteligentemente basado en contenido
     */
    private function categorizeNews(string $title, string $content, array $suggestedTypes): ?NewsType
    {
        $titleContent = strtolower($title . ' ' . $content);
        
        // Palabras clave por categoría
        $categoryKeywords = [
            'Energía y Tecnología' => [
                'energía', 'petróleo', 'gas', 'electricidad', 'renovable', 'solar', 'eólica',
                'tecnología', 'digital', 'internet', 'software', 'inteligencia artificial',
                'pemex', 'cfe', 'tecnológico', 'innovación', 'startup'
            ],
            'Economía Nacional' => [
                'economía', 'pib', 'inflación', 'banco', 'banxico', 'peso', 'dólar',
                'crecimiento', 'inversión', 'mercado', 'bolsa', 'financiero', 'fiscal',
                'hacienda', 'shcp', 'tipo de cambio', 'deuda'
            ],
            'Industria y Negocios' => [
                'empresa', 'negocio', 'industria', 'comercio', 'ventas', 'producción',
                'manufactura', 'exportación', 'importación', 'comercial', 'corporativo',
                'facturación', 'ganancia', 'utilidad', 'mercadotecnia'
            ],
            'Administración y Finanzas' => [
                'finanzas', 'contabilidad', 'presupuesto', 'recursos humanos', 'rh',
                'administración', 'gestión', 'auditoría', 'compras', 'adquisiciones',
                'nomina', 'salario', 'empleado'
            ],
            'Contratos' => [
                'contrato', 'licitación', 'proyecto', 'obra', 'construcción',
                'adjudicación', 'concurso', 'propuesta', 'gobierno', 'público'
            ],
            'QHSE' => [
                'seguridad', 'salud', 'medio ambiente', 'calidad', 'norma', 'certificación',
                'accidente', 'riesgo', 'protocolo', 'sustentable', 'sostenible', 'iso'
            ],
            'Ingeniería y Manufactura' => [
                'ingeniería', 'manufactura', 'fábrica', 'planta', 'producción', 'proceso',
                'maquinaria', 'equipo', 'técnico', 'industrial', 'automatización'
            ],
            'Operaciones' => [
                'operaciones', 'logística', 'transporte', 'distribución', 'almacén',
                'cadena de suministro', 'mantenimiento', 'soldadura', 'operativo'
            ],
            'Servicios Generales y Almacén' => [
                'almacén', 'inventario', 'servicios generales', 'it', 'sistemas',
                'infraestructura', 'facilities', 'soporte técnico'
            ],
            'Dirección General' => [
                'estrategia', 'dirección', 'liderazgo', 'corporativo', 'ejecutivo',
                'ceo', 'director', 'presidente', 'junta directiva', 'gobierno corporativo'
            ]
        ];

        // Calcular scores por categoría
        $scores = [];
        foreach ($categoryKeywords as $category => $keywords) {
            $score = 0;
            foreach ($keywords as $keyword) {
                if (str_contains($titleContent, $keyword)) {
                    // Dar más peso si aparece en el título
                    $weight = str_contains(strtolower($title), $keyword) ? 3 : 1;
                    $score += $weight;
                }
            }
            $scores[$category] = $score;
        }

        // Ordenar por score descendente
        arsort($scores);
        
        // Buscar la categoría con mayor score que esté disponible
        foreach ($scores as $category => $score) {
            if ($score > 0) {
                $newsType = NewsType::where('name', $category)->first();
                if ($newsType) {
                    Log::info("Categorized '{$title}' as '{$category}' (score: {$score})");
                    return $newsType;
                }
            }
        }

        // Si no hay match específico, usar categorías sugeridas
        $newsType = NewsType::whereIn('name', $suggestedTypes)->first();
        if ($newsType) {
            Log::info("Used suggested category '{$newsType->name}' for: {$title}");
            return $newsType;
        }

        // Fallback a Economía Nacional
        $fallback = NewsType::where('name', 'Economía Nacional')->first();
        if ($fallback) {
            Log::info("Used fallback category 'Economía Nacional' for: {$title}");
            return $fallback;
        }

        return null;
    }

    /**
     * Descargar y guardar imagen
     */
    private function downloadAndSaveImage(string $imageUrl, string $articleTitle): ?string
    {
        try {
            if (empty($imageUrl)) {
                return null;
            }

            // Crear directorio si no existe
            $storageDir = storage_path('app/public/news-images');
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            // Generar nombre único para la imagen
            $fileName = Str::slug($articleTitle) . '_' . time() . '.jpg';
            $filePath = $storageDir . '/' . $fileName;

            // Descargar imagen
            $response = $this->client->get($imageUrl, [
                'timeout' => 15,
                'sink' => $filePath
            ]);

            if ($response->getStatusCode() === 200 && file_exists($filePath)) {
                // Verificar que sea una imagen válida
                $imageInfo = getimagesize($filePath);
                if ($imageInfo !== false) {
                    Log::info("Downloaded image: {$fileName}");
                    return 'storage/news-images/' . $fileName;
                } else {
                    unlink($filePath);
                    Log::warning("Invalid image file downloaded: {$imageUrl}");
                }
            }

        } catch (\Exception $e) {
            Log::warning("Failed to download image {$imageUrl}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Extraer contenido completo del artículo
     */
    private function extractFullContent(string $articleUrl, string $source): ?string
    {
        try {
            Log::info("Extracting full content from: {$articleUrl}");
            
            $response = $this->client->get($articleUrl, [
                'timeout' => 20,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ]
            ]);

            $html = $response->getBody()->getContents();
            $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new DOMXPath($dom);

            // Selectores específicos por fuente para contenido completo
            $contentSelectors = $this->getContentSelectors($source);
            
            $fullContent = '';
            foreach ($contentSelectors as $selector) {
                $contentNodes = $xpath->query($selector);
                if ($contentNodes->length > 0) {
                    foreach ($contentNodes as $node) {
                        $text = trim($node->textContent);
                        if (strlen($text) > 50) { // Solo párrafos significativos
                            $fullContent .= $text . "\n\n";
                        }
                    }
                    break; // Use the first successful selector
                }
            }

            if (strlen($fullContent) > 200) {
                Log::info("Extracted " . strlen($fullContent) . " characters of content");
                return trim($fullContent);
            }

        } catch (\Exception $e) {
            Log::warning("Failed to extract full content from {$articleUrl}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Selectores para extraer contenido completo por fuente
     */
    private function getContentSelectors(string $source): array
    {
        $selectors = [
            'eluniversal' => [
                '//div[contains(@class, "nota-texto")]//p',
                '//div[contains(@class, "story-body")]//p',
                '//div[contains(@class, "articulo")]//p',
                '//article//p[string-length(text()) > 50]'
            ],
            'elfinanciero' => [
                '//div[contains(@class, "story-body")]//p',
                '//div[contains(@class, "article-content")]//p',
                '//div[contains(@class, "nota-texto")]//p',
                '//article//p[string-length(text()) > 50]'
            ],
            'milenio' => [
                '//div[contains(@class, "story-body")]//p',
                '//div[contains(@class, "nota-texto")]//p',
                '//div[contains(@class, "article-body")]//p',
                '//article//p[string-length(text()) > 50]'
            ]
        ];

        return $selectors[$source] ?? [
            '//article//p[string-length(text()) > 50]',
            '//div[contains(@class, "content")]//p',
            '//div[contains(@class, "story")]//p',
            '//main//p[string-length(text()) > 50]'
        ];
    }
}