<?php

namespace App\Services;

use App\Models\Recommendation;
use App\Models\RecommendationType;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;

class RecommendationScrapingService
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
            'Administración y Finanzas' => [
                'Recursos Humanos' => 'https://www.forbes.com.mx/negocios/',
                'Finanzas' => 'https://www.elfinanciero.com.mx/',
                'Compras' => 'https://expansion.mx/negocios',
                'Administración' => 'https://expansion.mx/empresas'
            ],
            'Contratos' => [
                'Proyectos' => 'https://compranet.hacienda.gob.mx/',
                'Comercial' => 'https://www.eleconomista.com.mx/negocios',
                'Contratos' => 'https://compranet.hacienda.gob.mx/licitaciones'
            ],
            'Dirección General' => [
                'Dirección General' => 'https://www.forbes.com.mx/liderazgo/',
                'Marketing' => 'https://www.merca20.com/'
            ],
            'Ingeniería y Manufactura' => [
                'Manufactura' => 'https://www.manufactura.mx/',
                'Ingeniería' => 'https://www.industriamexicana.mx/'
            ],
            'Operaciones' => [
                'Soldadura' => 'https://www.aws.org/publications/wj/',
                'HT & LS' => 'https://www.mantenimientocorrectivo.com/',
                'Mantenimiento Especializado' => 'https://www.industriamexicana.mx/mantenimiento'
            ],
            'QHSE' => [
                'QHSE' => 'https://www.iso.org/news.html',
                'Calidad' => 'https://www.seguridadindustrial.com.mx/'
            ],
            'Servicios Generales y Almacén' => [
                'Logística' => 'https://www.logisticahoy.com.mx/',
                'Servicios Generales' => 'https://www.scmexico.com/',
                'IT' => 'https://www.cio.com.mx/',
                'Almacén' => 'https://www.logisticahoy.com.mx/',
                'Seguridad Patrimonial' => 'https://www.seguridadempresarial.com.mx/'
            ]
        ];
    }

    /**
     * Obtener recomendaciones de todas las fuentes
     */
    public function scrapeAllSources(): array
    {
        $results = [
            'success' => 0,
            'errors' => 0,
            'total' => 0,
            'details' => []
        ];

        foreach ($this->sources as $department => $subAreas) {
            $departmentResult = $this->scrapeDepartment($department, $subAreas);
            $results['success'] += $departmentResult['success'];
            $results['errors'] += $departmentResult['errors'];
            $results['total'] += $departmentResult['total'];
            $results['details'][$department] = $departmentResult;
        }

        return $results;
    }

    /**
     * Obtener recomendaciones de un departamento específico
     */
    public function scrapeDepartment(string $department, array $subAreas): array
    {
        $results = [
            'success' => 0,
            'errors' => 0,
            'total' => 0,
            'sub_areas' => []
        ];

        Log::info("Iniciando scraping de departamento: {$department}");

        foreach ($subAreas as $subArea => $url) {
            try {
                $subAreaResult = $this->scrapeSubArea($department, $subArea, $url);
                $results['success'] += $subAreaResult['success'];
                $results['errors'] += $subAreaResult['errors'];
                $results['total'] += $subAreaResult['total'];
                $results['sub_areas'][$subArea] = $subAreaResult;

            } catch (\Exception $e) {
                Log::error("Error scraping {$department}/{$subArea}: " . $e->getMessage());
                $results['errors']++;
                $results['total']++;
            }
        }

        return $results;
    }

    /**
     * Obtener recomendaciones de una sub-área específica
     */
    private function scrapeSubArea(string $department, string $subArea, string $url): array
    {
        $results = [
            'success' => 0,
            'errors' => 0,
            'total' => 0,
            'recommendations' => []
        ];

        try {
            Log::info("Scraping sub-area: {$department}/{$subArea} - {$url}");

            $response = $this->client->get($url);
            $html = $response->getBody()->getContents();

            $recommendations = $this->extractRecommendations($html, $department, $subArea, $url);

            foreach ($recommendations as $recommendation) {
                try {
                    $saved = $this->saveRecommendation($recommendation, $department, $subArea);
                    if ($saved) {
                        $results['success']++;
                        $results['recommendations'][] = $recommendation['title'];
                    } else {
                        $results['errors']++;
                    }
                    $results['total']++;
                } catch (\Exception $e) {
                    Log::error("Error saving recommendation: " . $e->getMessage());
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
     * Extraer recomendaciones del HTML
     */
    private function extractRecommendations(string $html, string $department, string $subArea, string $baseUrl): array
    {
        $recommendations = [];

        // Limpiar HTML
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        // Selectores genéricos para artículos/contenido
        $selectors = $this->getRecommendationSelectors($baseUrl);

        foreach ($selectors as $selector) {
            $nodes = $xpath->query($selector['query']);

            foreach ($nodes as $node) {
                try {
                    $recommendation = $this->extractRecommendationFromNode($node, $selector, $baseUrl, $xpath);
                    if ($recommendation && $this->isValidRecommendation($recommendation)) {
                        // Transformar a formato de recomendación
                        $recommendation['transformed_content'] = $this->transformToRecommendation($recommendation, $department, $subArea);
                        $recommendations[] = $recommendation;
                    }
                } catch (\Exception $e) {
                    Log::warning("Error extracting recommendation: " . $e->getMessage());
                }

                // Limitar a 5 recomendaciones por sub-área para no sobrecargar
                if (count($recommendations) >= 5) {
                    break 2;
                }
            }
        }

        return $recommendations;
    }

    /**
     * Obtener selectores específicos por dominio
     */
    private function getRecommendationSelectors(string $url): array
    {
        $domain = parse_url($url, PHP_URL_HOST);
        
        $selectors = [
            'forbes.com.mx' => [
                [
                    'query' => '//article[contains(@class, "story")] | //div[contains(@class, "story-card")]',
                    'title' => './/h2/a | .//h3/a | .//h1',
                    'link' => './/a/@href',
                    'summary' => './/p[position()<=2 and string-length(text())>50]',
                    'image' => './/img/@src'
                ]
            ],
            'elfinanciero.com.mx' => [
                [
                    'query' => '//article | //div[contains(@class, "story")]',
                    'title' => './/h2 | .//h3 | .//h1',
                    'link' => './/a/@href',
                    'summary' => './/p[position()<=2 and string-length(text())>50]',
                    'image' => './/img/@src'
                ]
            ],
            'expansion.mx' => [
                [
                    'query' => '//article | //div[contains(@class, "article")]',
                    'title' => './/h2 | .//h3 | .//h1',
                    'link' => './/a/@href',
                    'summary' => './/p[position()<=2 and string-length(text())>50]',
                    'image' => './/img/@src'
                ]
            ]
        ];

        return $selectors[$domain] ?? [
            [
                'query' => '//article | //div[contains(@class, "article")] | //div[contains(@class, "story")] | //div[contains(@class, "post")]',
                'title' => './/h1 | .//h2 | .//h3 | .//h4',
                'link' => './/a/@href',
                'summary' => './/p[position()<=3 and string-length(text())>30]',
                'image' => './/img/@src'
            ]
        ];
    }

    /**
     * Extraer datos de la recomendación desde el nodo DOM
     */
    private function extractRecommendationFromNode($node, array $selector, string $baseUrl, DOMXPath $xpath): ?array
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

        // Extraer resumen/contenido
        $summaryNodes = $xpath->query($selector['summary'], $node);
        $summary = '';
        if ($summaryNodes->length > 0) {
            foreach ($summaryNodes as $summaryNode) {
                $text = trim($summaryNode->textContent);
                if (strlen($text) > 30) {
                    $summary .= $text . ' ';
                }
            }
        }

        // Extraer imagen
        $imageNodes = $xpath->query($selector['image'], $node);
        $image = null;
        if ($imageNodes->length > 0) {
            $image = $imageNodes->item(0)->nodeValue;
            $image = $this->normalizeUrl($image, $baseUrl);
        }

        if (!$title || strlen($title) < 10) {
            return null;
        }

        return [
            'title' => $title,
            'link' => $link,
            'summary' => trim($summary) ?: substr($title, 0, 200),
            'image_url' => $image,
            'scraped_at' => Carbon::now()
        ];
    }

    /**
     * Transformar contenido en formato de recomendación
     */
    private function transformToRecommendation(array $content, string $department, string $subArea): string
    {
        $title = $content['title'];
        $summary = $content['summary'];
        
        // Templates por área para dar contexto de recomendación
        $templates = [
            'Recursos Humanos' => "💼 RECOMENDACIÓN PARA RECURSOS HUMANOS\n\n📋 Basado en: {$title}\n\n🎯 Aplicación práctica: {$summary}\n\n✅ Implementación sugerida: Considere aplicar estos conceptos en la gestión de talento y desarrollo organizacional.",
            'Finanzas' => "💰 RECOMENDACIÓN FINANCIERA\n\n📊 Insight: {$title}\n\n📈 Análisis: {$summary}\n\n🎯 Acción recomendada: Evalúe la implementación de estas estrategias financieras en su planificación presupuestaria.",
            'Compras' => "🛒 RECOMENDACIÓN PARA COMPRAS\n\n🎯 Oportunidad: {$title}\n\n📋 Detalle: {$summary}\n\n✅ Sugerencia: Considere estas prácticas para optimizar los procesos de adquisiciones.",
            'Marketing' => "📢 ESTRATEGIA DE MARKETING\n\n🎯 Tendencia: {$title}\n\n📊 Análisis: {$summary}\n\n💡 Recomendación: Adapte estas estrategias a su plan de marketing digital.",
            'Manufactura' => "🏭 OPTIMIZACIÓN DE MANUFACTURA\n\n⚙️ Proceso: {$title}\n\n🔧 Descripción: {$summary}\n\n📈 Beneficio: Implemente estas mejoras para aumentar la eficiencia productiva.",
            'QHSE' => "🛡️ RECOMENDACIÓN DE QHSE\n\n🔍 Estándar: {$title}\n\n📋 Detalle: {$summary}\n\n✅ Implementación: Adopte estas prácticas para mejorar la seguridad y calidad.",
            'Logística' => "🚚 OPTIMIZACIÓN LOGÍSTICA\n\n📦 Estrategia: {$title}\n\n🔄 Proceso: {$summary}\n\n⏱️ Beneficio: Mejore la eficiencia de la cadena de suministro con estas prácticas."
        ];

        return $templates[$subArea] ?? "💡 RECOMENDACIÓN PARA {$subArea}\n\n📌 {$title}\n\n{$summary}\n\nConsidere la aplicación de estas mejores prácticas en su área de trabajo.";
    }

    /**
     * Normalizar URL
     */
    private function normalizeUrl(string $url, string $baseUrl): string
    {
        if (empty($url)) {
            return '';
        }

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        if (str_starts_with($url, '//')) {
            return 'https:' . $url;
        }

        if (str_starts_with($url, '/')) {
            $parsedBase = parse_url($baseUrl);
            return $parsedBase['scheme'] . '://' . $parsedBase['host'] . $url;
        }

        return $baseUrl . '/' . ltrim($url, '/');
    }

    /**
     * Validar si la recomendación es válida
     */
    private function isValidRecommendation(array $recommendation): bool
    {
        if (empty($recommendation['title']) || strlen($recommendation['title']) < 20) {
            return false;
        }

        if (strlen($recommendation['summary']) < 50) {
            return false;
        }

        if ($this->isExcludedContent($recommendation['title'])) {
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
            'publicidad', 'newsletter', 'suscríbete', 'síguenos',
            'video', 'galería', 'fotos', 'podcast', 'radio',
            'comentarios', 'opinión', 'editorial', 'blog'
        ];

        $titleLower = strtolower($title);
        foreach ($excludePatterns as $pattern) {
            if (str_contains($titleLower, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Guardar recomendación en la base de datos
     */
    private function saveRecommendation(array $recommendation, string $department, string $subArea): bool
    {
        try {
            // Verificar duplicados
            $existingRecommendation = Recommendation::where('title', $recommendation['title'])
                ->orWhere('external_link', $recommendation['link'])
                ->first();
                
            if ($existingRecommendation) {
                Log::debug("Recommendation already exists: " . $recommendation['title']);
                return false;
            }

            // Obtener el tipo de recomendación
            $recommendationType = RecommendationType::where('name', $department)->first();
            if (!$recommendationType) {
                Log::warning("Recommendation type not found: {$department}");
                return false;
            }

            // Descargar imagen si está disponible
            $localImagePath = null;
            if (!empty($recommendation['image_url'])) {
                $localImagePath = $this->downloadAndSaveImage($recommendation['image_url'], $recommendation['title']);
            }

            // Crear la recomendación
            Recommendation::create([
                'title' => substr($recommendation['title'], 0, 255),
                'description' => $recommendation['summary'],
                'content' => $recommendation['transformed_content'],
                'image' => $localImagePath,
                'external_link' => $recommendation['link'],
                'image_url' => $recommendation['image_url'],
                'source' => parse_url($recommendation['link'], PHP_URL_HOST),
                'sub_area' => $subArea,
                'recommendation_type_id' => $recommendationType->id,
                'is_scraped' => true,
                'scraped_at' => $recommendation['scraped_at'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            Log::info("Saved recommendation: " . $recommendation['title'] . " -> {$department}/{$subArea}");
            return true;

        } catch (\Exception $e) {
            Log::error("Error saving recommendation '{$recommendation['title']}': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Descargar y guardar imagen
     */
    private function downloadAndSaveImage(string $imageUrl, string $title): ?string
    {
        try {
            if (empty($imageUrl)) {
                return null;
            }

            $storageDir = storage_path('app/public/recommendation-images');
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            $fileName = Str::slug($title) . '_' . time() . '.jpg';
            $filePath = $storageDir . '/' . $fileName;

            $response = $this->client->get($imageUrl, [
                'timeout' => 15,
                'sink' => $filePath
            ]);

            if ($response->getStatusCode() === 200 && file_exists($filePath)) {
                $imageInfo = getimagesize($filePath);
                if ($imageInfo !== false) {
                    Log::info("Downloaded recommendation image: {$fileName}");
                    return 'storage/recommendation-images/' . $fileName;
                } else {
                    unlink($filePath);
                }
            }

        } catch (\Exception $e) {
            Log::warning("Failed to download recommendation image {$imageUrl}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Obtener estadísticas del scraping
     */
    public function getScrapingStats(): array
    {
        return [
            'total_scraped_recommendations' => Recommendation::where('is_scraped', true)->count(),
            'today_scraped' => Recommendation::where('is_scraped', true)
                ->whereDate('scraped_at', Carbon::today())
                ->count(),
            'by_department' => Recommendation::where('is_scraped', true)
                ->join('recommendations_type', 'recommendations.recommendation_type_id', '=', 'recommendations_type.id')
                ->selectRaw('recommendations_type.name, COUNT(*) as count')
                ->groupBy('recommendations_type.name')
                ->pluck('count', 'name')
                ->toArray(),
            'by_sub_area' => Recommendation::where('is_scraped', true)
                ->selectRaw('sub_area, COUNT(*) as count')
                ->groupBy('sub_area')
                ->pluck('count', 'sub_area')
                ->toArray(),
            'last_scraping' => Recommendation::where('is_scraped', true)
                ->orderBy('scraped_at', 'desc')
                ->first()?->scraped_at
        ];
    }

    /**
     * Limpiar recomendaciones antiguas
     */
    public function cleanOldRecommendations(int $daysOld = 60): int
    {
        $cutoffDate = Carbon::now()->subDays($daysOld);
        
        return Recommendation::where('is_scraped', true)
            ->where('created_at', '<', $cutoffDate)
            ->delete();
    }
}