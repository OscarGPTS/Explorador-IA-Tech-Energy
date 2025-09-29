<?php

namespace App\Services;

use App\Models\Recommendation;
use App\Models\RecommendationType;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TestRecommendationScrapingService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 15,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ]
        ]);
    }

    /**
     * Scrape test recommendations from reliable sources
     */
    public function scrapeTestSources(): array
    {
        $results = [
            'total' => 0,
            'success' => 0,
            'errors' => 0,
            'details' => []
        ];

        // Test sources with working websites
        $testSources = [
            'Dirección General' => [
                'name' => 'Forbes Business',
                'url' => 'https://www.forbes.com/',
                'selector' => '//h2 | //h3',
                'sub_area' => 'Estrategia Empresarial'
            ],
            'Administración y Finanzas' => [
                'name' => 'BBC Business',
                'url' => 'https://www.bbc.com/news/business',
                'selector' => '//h2 | //h3',
                'sub_area' => 'Finanzas Corporativas'
            ]
        ];

        foreach ($testSources as $departmentName => $source) {
            Log::info("Testing scraping for department: {$departmentName}");
            
            $departmentResult = $this->scrapeDepartmentTest($departmentName, $source);
            
            $results['total'] += $departmentResult['total'];
            $results['success'] += $departmentResult['success'];
            $results['errors'] += $departmentResult['errors'];
            $results['details'][$departmentName] = $departmentResult;
        }

        return $results;
    }

    /**
     * Scrape a single test department
     */
    private function scrapeDepartmentTest(string $departmentName, array $source): array
    {
        $result = [
            'total' => 0,
            'success' => 0,
            'errors' => 0
        ];

        try {
            // Find the recommendation type
            $recommendationType = RecommendationType::where('name', $departmentName)->first();
            
            if (!$recommendationType) {
                Log::warning("Recommendation type not found: {$departmentName}");
                $result['errors']++;
                return $result;
            }

            Log::info("Scraping test source: {$source['name']} - {$source['url']}");

            $response = $this->client->get($source['url']);
            $html = (string) $response->getBody();

            if (empty($html)) {
                Log::warning("Empty response from {$source['url']}");
                $result['errors']++;
                return $result;
            }

            $dom = new \DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);

            // Get headlines as potential recommendations
            $elements = $xpath->query($source['selector']);
            $count = 0;

            foreach ($elements as $element) {
                if ($count >= 3) break; // Limit for testing
                
                $title = trim($element->textContent);
                
                if (strlen($title) < 20 || strlen($title) > 200) {
                    continue;
                }

                // Extraer link del artículo si está disponible
                $articleLink = $this->extractArticleLink($element, $source['url']);

                $recommendation = $this->createTestRecommendation(
                    $title,
                    $recommendationType,
                    $articleLink ?: $source['url'],
                    $source['name'],
                    $source['sub_area']
                );

                $result['total']++;
                if ($recommendation) {
                    $result['success']++;
                    $count++;
                    Log::info("Created test recommendation: " . Str::limit($title, 50));
                } else {
                    $result['errors']++;
                }
            }

        } catch (RequestException $e) {
            Log::error("HTTP Error scraping {$source['url']}: " . $e->getMessage());
            $result['errors']++;
        } catch (\Exception $e) {
            Log::error("Error scraping {$departmentName}: " . $e->getMessage());
            $result['errors']++;
        }

        return $result;
    }

    /**
     * Create a test recommendation
     */
    private function createTestRecommendation(
        string $title, 
        RecommendationType $type, 
        string $url, 
        string $source,
        string $subArea
    ): ?Recommendation {
        try {
            // Extraer contenido completo del artículo
            $fullContent = $this->extractFullContent($url);
            $imageUrl = $this->extractMainImage($url);
            
            // Check for duplicates
            $exists = Recommendation::where('title', $title)
                ->where('recommendation_type_id', $type->id)
                ->exists();

            if ($exists) {
                Log::info("Duplicate recommendation found, skipping: " . $title);
                return null;
            }

            return Recommendation::create([
                'title' => $title,
                'description' => $title, // Título original como descripción temporal
                'content' => $fullContent ?: $title, // Contenido completo o título como fallback
                'recommendation_type_id' => $type->id,
                'external_link' => $url,
                'image_url' => $imageUrl,
                'source' => $source,
                'sub_area' => $subArea,
                'is_scraped' => true,
                'scraped_at' => now(),
            ]);

        } catch (\Exception $e) {
            Log::error("Error creating recommendation: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Transform a news title into a recommendation
     */
    private function transformToRecommendation(string $title, string $department, string $subArea): array
    {
        // Solo usar el título original y el contenido como descripción
        return [
            'title' => $title,
            'description' => $title // Usar el título original como descripción también
        ];
    }

    /**
     * Get scraping statistics
     */
    public function getStats(): array
    {
        return [
            'total_scraped' => Recommendation::where('is_scraped', true)->count(),
            'today_scraped' => Recommendation::where('is_scraped', true)
                ->whereDate('scraped_at', today())
                ->count(),
            'by_department' => Recommendation::where('is_scraped', true)
                ->with('recommendationType')
                ->get()
                ->groupBy('recommendationType.name')
                ->map(fn($group) => $group->count())
                ->toArray()
        ];
    }

    /**
     * Extraer link del artículo desde el elemento HTML
     */
    private function extractArticleLink(\DOMElement $element, string $baseUrl): ?string
    {
        try {
            // Buscar enlace en el elemento padre o hijo
            $linkElement = null;
            
            // Verificar si el elemento mismo es un enlace
            if ($element->tagName === 'a') {
                $linkElement = $element;
            } else {
                // Buscar enlace en elementos padre
                $parent = $element->parentNode;
                while ($parent && $parent instanceof \DOMElement) {
                    if ($parent->tagName === 'a') {
                        $linkElement = $parent;
                        break;
                    }
                    $parent = $parent->parentNode;
                }
                
                // Si no se encuentra en padre, buscar en hijos
                if (!$linkElement) {
                    $links = $element->getElementsByTagName('a');
                    if ($links->length > 0) {
                        $firstLink = $links->item(0);
                        if ($firstLink instanceof \DOMElement) {
                            $linkElement = $firstLink;
                        }
                    }
                }
            }
            
            if ($linkElement instanceof \DOMElement && $linkElement->hasAttribute('href')) {
                $href = $linkElement->getAttribute('href');
                
                // Convertir enlaces relativos a absolutos
                if (strpos($href, 'http') !== 0) {
                    $parsedBase = parse_url($baseUrl);
                    if ($parsedBase && isset($parsedBase['scheme']) && isset($parsedBase['host'])) {
                        $baseHost = $parsedBase['scheme'] . '://' . $parsedBase['host'];
                        
                        if (strpos($href, '/') === 0) {
                            $href = $baseHost . $href;
                        } else {
                            $href = rtrim($baseUrl, '/') . '/' . ltrim($href, '/');
                        }
                    }
                }
                
                return $href;
            }
            
        } catch (\Exception $e) {
            Log::warning("Error extracting article link: " . $e->getMessage());
        }
        
        return null;
    }

    /**
     * Extraer contenido completo del artículo
     */
    private function extractFullContent(string $articleUrl): ?string
    {
        try {
            Log::info("Extracting full content from: {$articleUrl}");
            
            $response = $this->client->get($articleUrl, [
                'timeout' => 15,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ]
            ]);

            $html = $response->getBody()->getContents();
            $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

            $dom = new \DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);

            // Selectores generales para contenido de artículos
            $contentSelectors = [
                '//article//p[string-length(text()) > 50]',
                '//div[contains(@class, "content")]//p[string-length(text()) > 50]',
                '//div[contains(@class, "article")]//p[string-length(text()) > 50]',
                '//div[contains(@class, "story")]//p[string-length(text()) > 50]',
                '//div[contains(@class, "text")]//p[string-length(text()) > 50]',
                '//div[contains(@class, "body")]//p[string-length(text()) > 50]',
                '//main//p[string-length(text()) > 50]',
                '//p[string-length(text()) > 100]' // Fallback para párrafos largos
            ];
            
            $fullContent = '';
            foreach ($contentSelectors as $selector) {
                $contentNodes = $xpath->query($selector);
                if ($contentNodes && $contentNodes->length > 0) {
                    foreach ($contentNodes as $node) {
                        $text = trim($node->textContent);
                        if (strlen($text) > 50 && !str_contains($fullContent, $text)) {
                            $fullContent .= $text . "\n\n";
                        }
                    }
                    if (strlen($fullContent) > 200) {
                        break; // Ya tenemos suficiente contenido
                    }
                }
            }

            if (strlen($fullContent) > 200) {
                $cleanContent = $this->cleanContent($fullContent);
                Log::info("Extracted " . strlen($cleanContent) . " characters of content");
                return $cleanContent;
            }

        } catch (\Exception $e) {
            Log::warning("Failed to extract full content from {$articleUrl}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Extraer imagen principal del artículo
     */
    private function extractMainImage(string $articleUrl): ?string
    {
        try {
            $response = $this->client->get($articleUrl, [
                'timeout' => 10,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ]
            ]);

            $html = $response->getBody()->getContents();
            $dom = new \DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);

            // Selectores para imágenes principales
            $imageSelectors = [
                '//meta[@property="og:image"]/@content',
                '//meta[@name="twitter:image"]/@content',
                '//article//img[1]/@src',
                '//div[contains(@class, "featured")]//img/@src',
                '//div[contains(@class, "hero")]//img/@src',
                '//div[contains(@class, "main")]//img/@src',
                '//img[contains(@class, "featured")]/@src',
                '//img[1]/@src' // Fallback a la primera imagen
            ];

            foreach ($imageSelectors as $selector) {
                $imageNodes = $xpath->query($selector);
                if ($imageNodes && $imageNodes->length > 0) {
                    $imageSrc = $imageNodes->item(0)->nodeValue;
                    
                    if ($imageSrc && $this->isValidImageUrl($imageSrc)) {
                        // Convertir URLs relativas a absolutas
                        if (strpos($imageSrc, 'http') !== 0) {
                            $parsedBase = parse_url($articleUrl);
                            if ($parsedBase && isset($parsedBase['scheme']) && isset($parsedBase['host'])) {
                                $baseHost = $parsedBase['scheme'] . '://' . $parsedBase['host'];
                                if (strpos($imageSrc, '/') === 0) {
                                    $imageSrc = $baseHost . $imageSrc;
                                } else {
                                    $imageSrc = rtrim($articleUrl, '/') . '/' . ltrim($imageSrc, '/');
                                }
                            }
                        }
                        
                        Log::info("Extracted image: {$imageSrc}");
                        return $imageSrc;
                    }
                }
            }

        } catch (\Exception $e) {
            Log::warning("Failed to extract image from {$articleUrl}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Limpiar contenido de caracteres innecesarios
     */
    private function cleanContent(string $content): string
    {
        // Remover múltiples espacios en blanco y saltos de línea
        $content = preg_replace('/\s+/', ' ', $content);
        $content = preg_replace('/\n{3,}/', "\n\n", $content);
        
        // Remover patrones comunes de spam o navegación
        $patterns = [
            '/\b(compartir|share|tweet|facebook|instagram|whatsapp|telegram)\b/i',
            '/\b(suscribirse|subscribe|newsletter|follow us)\b/i',
            '/\b(más noticias|related news|you may also like)\b/i',
            '/\b(advertisement|publicidad|sponsored)\b/i',
            '/^\s*[-•*]\s*/m', // Bullets al inicio de línea
        ];
        
        foreach ($patterns as $pattern) {
            $content = preg_replace($pattern, '', $content);
        }
        
        return trim($content);
    }

    /**
     * Validar si una URL es una imagen válida
     */
    private function isValidImageUrl(string $url): bool
    {
        // Verificar extensiones de imagen comunes
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
        
        if (in_array($extension, $imageExtensions)) {
            return true;
        }
        
        // Verificar patrones comunes de URLs de imagen
        if (preg_match('/\.(jpg|jpeg|png|gif|webp|svg)(\?|$)/i', $url)) {
            return true;
        }
        
        // Verificar que no sean iconos muy pequeños
        if (str_contains($url, 'favicon') || str_contains($url, 'icon') || str_contains($url, 'logo')) {
            return false;
        }
        
        return strlen($url) > 10; // URL mínima válida
    }
}