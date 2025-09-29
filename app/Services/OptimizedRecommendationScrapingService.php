<?php

namespace App\Services;

use App\Models\Recommendation;
use App\Models\RecommendationType;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OptimizedRecommendationScrapingService
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
     * URLs verificadas que funcionan correctamente
     */
    private function getWorkingUrls(): array
    {
        return [
            'Administración y Finanzas' => [
                'Recursos Humanos' => [
                    [
                        'url' => 'https://www.forbes.com.mx/categoria/capital-humano/',
                        'name' => 'Forbes Capital Humano',
                        'selector' => '//h2[@class="title"] | //h3[@class="title"] | //h2[contains(@class,"entry-title")]'
                    ],
                    [
                        'url' => 'https://www.elfinanciero.com.mx/empresas/',
                        'name' => 'El Financiero Empresas',
                        'selector' => '//h2[contains(@class,"headline")] | //h3[contains(@class,"headline")]'
                    ],
                    [
                        'url' => 'https://www.gestiopolis.com/categoria/talento-humano/',
                        'name' => 'Gestiopolis Talento Humano',
                        'selector' => '//h2[contains(@class,"entry-title")] | //h3'
                    ]
                ],
                'Finanzas' => [
                    [
                        'url' => 'https://www.forbes.com.mx/categoria/finanzas/',
                        'name' => 'Forbes Finanzas',
                        'selector' => '//h2[@class="title"] | //h3[@class="title"]'
                    ],
                    [
                        'url' => 'https://www.elfinanciero.com.mx/mercados/',
                        'name' => 'El Financiero Mercados',
                        'selector' => '//h2[contains(@class,"headline")] | //h3[contains(@class,"headline")]'
                    ]
                ]
            ],
            'Contratos' => [
                'Legal' => [
                    [
                        'url' => 'https://www.scjn.gob.mx/',
                        'name' => 'Suprema Corte',
                        'selector' => '//h2 | //h3'
                    ]
                ]
            ],
            'Dirección General' => [
                'Estrategia' => [
                    [
                        'url' => 'https://www.forbes.com.mx/',
                        'name' => 'Forbes México',
                        'selector' => '//h2[@class="title"] | //h3[@class="title"]'
                    ],
                    [
                        'url' => 'https://www.elfinanciero.com.mx/',
                        'name' => 'El Financiero',
                        'selector' => '//h2[contains(@class,"headline")] | //h3[contains(@class,"headline")]'
                    ],
                    [
                        'url' => 'https://expansion.mx/',
                        'name' => 'Expansión',
                        'selector' => '//h2[contains(@class,"title")] | //h3[contains(@class,"title")]'
                    ]
                ],
                'Marketing' => [
                    [
                        'url' => 'https://www.merca20.com/',
                        'name' => 'Merca 2.0',
                        'selector' => '//h2[contains(@class,"entry-title")] | //h3'
                    ]
                ]
            ],
            'Operaciones' => [
                'Soldadura' => [
                    [
                        'url' => 'https://www.aws.org/publications/wj/',
                        'name' => 'AWS Welding Journal',
                        'selector' => '//h2 | //h3'
                    ]
                ]
            ],
            'QHSE' => [
                'Calidad' => [
                    [
                        'url' => 'https://www.iso.org/news.html',
                        'name' => 'ISO News',
                        'selector' => '//h2 | //h3'
                    ]
                ]
            ],
            'Servicios Generales y Almacén' => [
                'Servicios Generales' => [
                    [
                        'url' => 'https://www.scmexico.com/',
                        'name' => 'SCM México',
                        'selector' => '//h2 | //h3'
                    ]
                ]
            ]
        ];
    }

    /**
     * URLs alternativas internacionales para departamentos con pocas fuentes
     */
    private function getAlternativeUrls(): array
    {
        return [
            'Ingeniería y Manufactura' => [
                'Manufactura' => [
                    [
                        'url' => 'https://www.industryweek.com/',
                        'name' => 'Industry Week',
                        'selector' => '//h2 | //h3'
                    ],
                    [
                        'url' => 'https://www.manufacturingnews.com/',
                        'name' => 'Manufacturing News',
                        'selector' => '//h2 | //h3'
                    ]
                ]
            ],
            'Operaciones' => [
                'Mantenimiento' => [
                    [
                        'url' => 'https://www.reliableplant.com/',
                        'name' => 'Reliable Plant',
                        'selector' => '//h2 | //h3'
                    ],
                    [
                        'url' => 'https://www.plantengineering.com/',
                        'name' => 'Plant Engineering',
                        'selector' => '//h2 | //h3'
                    ]
                ]
            ],
            'Servicios Generales y Almacén' => [
                'Logística' => [
                    [
                        'url' => 'https://www.supplychainbrain.com/',
                        'name' => 'Supply Chain Brain',
                        'selector' => '//h2 | //h3'
                    ],
                    [
                        'url' => 'https://www.logisticsmgmt.com/',
                        'name' => 'Logistics Management',
                        'selector' => '//h2 | //h3'
                    ]
                ]
            ]
        ];
    }

    /**
     * Scrape recomendaciones por estrategia optimizada
     */
    public function scrapeOptimized(string $strategy = 'mixed'): array
    {
        $results = [
            'total' => 0,
            'success' => 0,
            'errors' => 0,
            'details' => [],
            'strategy' => $strategy
        ];

        switch ($strategy) {
            case 'working_only':
                return $this->scrapeWorkingUrls($results);
            case 'with_alternatives':
                return $this->scrapeWithAlternatives($results);
            case 'by_department':
                return $this->scrapeByDepartment($results);
            case 'mixed':
            default:
                return $this->scrapeMixed($results);
        }
    }

    /**
     * Estrategia 1: Solo URLs verificadas que funcionan
     */
    private function scrapeWorkingUrls(array $results): array
    {
        Log::info('🎯 Scraping strategy: Working URLs only');
        
        $workingUrls = $this->getWorkingUrls();
        
        foreach ($workingUrls as $departmentName => $subAreas) {
            $departmentResult = $this->scrapeDepartmentSources($departmentName, $subAreas);
            
            $results['total'] += $departmentResult['total'];
            $results['success'] += $departmentResult['success'];
            $results['errors'] += $departmentResult['errors'];
            $results['details'][$departmentName] = $departmentResult;
        }

        return $results;
    }

    /**
     * Estrategia 2: URLs que funcionan + alternativas internacionales
     */
    private function scrapeWithAlternatives(array $results): array
    {
        Log::info('🌍 Scraping strategy: Working URLs + International alternatives');
        
        // Primero las URLs que funcionan
        $results = $this->scrapeWorkingUrls($results);
        
        // Luego las alternativas para departamentos con pocas fuentes
        $alternatives = $this->getAlternativeUrls();
        
        foreach ($alternatives as $departmentName => $subAreas) {
            $departmentResult = $this->scrapeDepartmentSources($departmentName, $subAreas);
            
            $results['total'] += $departmentResult['total'];
            $results['success'] += $departmentResult['success'];
            $results['errors'] += $departmentResult['errors'];
            
            // Combinar con resultados existentes del departamento
            if (isset($results['details'][$departmentName])) {
                $existing = $results['details'][$departmentName];
                $results['details'][$departmentName] = [
                    'total' => $existing['total'] + $departmentResult['total'],
                    'success' => $existing['success'] + $departmentResult['success'],
                    'errors' => $existing['errors'] + $departmentResult['errors']
                ];
            } else {
                $results['details'][$departmentName] = $departmentResult;
            }
        }

        return $results;
    }

    /**
     * Estrategia 3: Procesar un departamento a la vez
     */
    private function scrapeByDepartment(array $results, string $targetDepartment = null): array
    {
        Log::info("🏢 Scraping strategy: By department" . ($targetDepartment ? " - {$targetDepartment}" : ""));
        
        $workingUrls = $this->getWorkingUrls();
        
        if ($targetDepartment) {
            if (isset($workingUrls[$targetDepartment])) {
                $departmentResult = $this->scrapeDepartmentSources($targetDepartment, $workingUrls[$targetDepartment]);
                
                $results['total'] = $departmentResult['total'];
                $results['success'] = $departmentResult['success'];
                $results['errors'] = $departmentResult['errors'];
                $results['details'][$targetDepartment] = $departmentResult;
            }
        } else {
            // Si no se especifica departamento, hacer todos uno por uno con pausa
            foreach ($workingUrls as $departmentName => $subAreas) {
                Log::info("Processing department: {$departmentName}");
                
                $departmentResult = $this->scrapeDepartmentSources($departmentName, $subAreas);
                
                $results['total'] += $departmentResult['total'];
                $results['success'] += $departmentResult['success'];
                $results['errors'] += $departmentResult['errors'];
                $results['details'][$departmentName] = $departmentResult;
                
                // Pausa entre departamentos para evitar sobrecargar servidores
                sleep(2);
            }
        }

        return $results;
    }

    /**
     * Estrategia 4: Mixta (la más balanceada)
     */
    private function scrapeMixed(array $results): array
    {
        Log::info('🎲 Scraping strategy: Mixed approach');
        
        // Primero los departamentos con más fuentes verificadas
        $highPriorityDepts = ['Administración y Finanzas', 'Dirección General'];
        $lowPriorityDepts = ['Ingeniería y Manufactura', 'Servicios Generales y Almacén'];
        
        $workingUrls = $this->getWorkingUrls();
        $alternatives = $this->getAlternativeUrls();
        
        // Procesar departamentos de alta prioridad con URLs verificadas
        foreach ($highPriorityDepts as $deptName) {
            if (isset($workingUrls[$deptName])) {
                $departmentResult = $this->scrapeDepartmentSources($deptName, $workingUrls[$deptName]);
                
                $results['total'] += $departmentResult['total'];
                $results['success'] += $departmentResult['success'];
                $results['errors'] += $departmentResult['errors'];
                $results['details'][$deptName] = $departmentResult;
            }
        }
        
        // Procesar departamentos de baja prioridad con alternativas
        foreach ($lowPriorityDepts as $deptName) {
            $sources = $alternatives[$deptName] ?? [];
            if (!empty($sources)) {
                $departmentResult = $this->scrapeDepartmentSources($deptName, $sources);
                
                $results['total'] += $departmentResult['total'];
                $results['success'] += $departmentResult['success'];
                $results['errors'] += $departmentResult['errors'];
                $results['details'][$deptName] = $departmentResult;
            }
        }
        
        // Procesar resto de departamentos con URLs verificadas
        foreach ($workingUrls as $deptName => $subAreas) {
            if (!in_array($deptName, $highPriorityDepts)) {
                $departmentResult = $this->scrapeDepartmentSources($deptName, $subAreas);
                
                $results['total'] += $departmentResult['total'];
                $results['success'] += $departmentResult['success'];
                $results['errors'] += $departmentResult['errors'];
                $results['details'][$deptName] = $departmentResult;
            }
        }

        return $results;
    }

    /**
     * Scrape fuentes de un departamento específico
     */
    protected function scrapeDepartmentSources(string $departmentName, array $subAreas): array
    {
        $result = [
            'total' => 0,
            'success' => 0,
            'errors' => 0
        ];

        // Buscar el tipo de recomendación
        $recommendationType = RecommendationType::where('name', $departmentName)->first();
        
        if (!$recommendationType) {
            Log::warning("Recommendation type not found: {$departmentName}");
            $result['errors']++;
            return $result;
        }

        Log::info("🏢 Scraping department: {$departmentName}");

        foreach ($subAreas as $subAreaName => $sources) {
            Log::info("  📂 Scraping sub-area: {$subAreaName}");
            
            foreach ($sources as $source) {
                $sourceResult = $this->scrapeSingleSource(
                    $source,
                    $recommendationType,
                    $subAreaName,
                    $departmentName
                );
                
                $result['total'] += $sourceResult['total'];
                $result['success'] += $sourceResult['success'];
                $result['errors'] += $sourceResult['errors'];
                
                // Pausa entre fuentes
                usleep(500000); // 0.5 segundos
            }
        }

        return $result;
    }

    /**
     * Scrape una fuente individual
     */
    private function scrapeSingleSource(array $source, RecommendationType $type, string $subArea, string $department): array
    {
        $result = ['total' => 0, 'success' => 0, 'errors' => 0];

        try {
            Log::info("    🔗 Scraping: {$source['name']} - {$source['url']}");

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

            $elements = $xpath->query($source['selector']);
            $count = 0;

            foreach ($elements as $element) {
                if ($count >= 3) break; // Límite por fuente
                
                $title = trim($element->textContent);
                
                if (strlen($title) < 20 || strlen($title) > 200) {
                    continue;
                }

                // Extraer link del artículo si está disponible
                $articleLink = $this->extractArticleLink($element, $source['url']);

                $recommendation = $this->createRecommendation(
                    $title,
                    $type,
                    $articleLink ?: $source['url'],
                    $source['name'],
                    $subArea,
                    $department
                );

                $result['total']++;
                if ($recommendation) {
                    $result['success']++;
                    $count++;
                    Log::info("      ✅ Created: " . Str::limit($title, 50));
                } else {
                    $result['errors']++;
                }
            }

        } catch (RequestException $e) {
            Log::error("HTTP Error scraping {$source['url']}: " . $e->getMessage());
            $result['errors']++;
        } catch (\Exception $e) {
            Log::error("Error scraping {$source['name']}: " . $e->getMessage());
            $result['errors']++;
        }

        return $result;
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
     * Crear una recomendación
     */
    private function createRecommendation(
        string $title, 
        RecommendationType $type, 
        string $url, 
        string $source,
        string $subArea,
        string $department
    ): ?Recommendation {
        try {
            // Transformar título en recomendación
            $recommendation = $this->transformToRecommendation($title, $department, $subArea);
            
            // Extraer contenido completo del artículo
            $fullContent = $this->extractFullContent($url);
            $imageUrl = $this->extractMainImage($url);
            
            // Verificar duplicados
            $exists = Recommendation::where('title', $recommendation['title'])
                ->where('recommendation_type_id', $type->id)
                ->exists();

            if ($exists) {
                Log::info("Duplicate recommendation found, skipping: " . $recommendation['title']);
                return null;
            }

            return Recommendation::create([
                'title' => $recommendation['title'],
                'description' => $recommendation['description'],
                'content' => $fullContent ?: $recommendation['description'], // Usar contenido completo o descripción como fallback
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
     * Transformar noticia en recomendación específica por departamento
     */
    private function transformToRecommendation(string $title, string $department, string $subArea): array
    {
        $templates = [
            'Administración y Finanzas' => [
                'Recursos Humanos' => [
                    'title' => "Implementar estrategia de talento basada en: {$title}",
                    'description' => $title ?: 'Mejora la gestión de recursos humanos para la atracción, retención y desarrollo del talento organizacional.'
                ],
                'Finanzas' => [
                    'title' => "Evaluar impacto financiero de: {$title}",
                    'description' => $title ?: 'Influye en la estructura de costos, presupuestos y estrategia financiera de la organización.'
                ]
            ],
            'Dirección General' => [
                'Estrategia' => [
                    'title' => "Considerar estrategia empresarial basada en: {$title}",
                    'description' => $title ?: 'Representa una oportunidad estratégica para el crecimiento y posicionamiento competitivo de la organización.'
                ],
                'Marketing' => [
                    'title' => "Aplicar tendencia de marketing: {$title}",
                    'description' => $title ?: 'Mejora la estrategia de marketing y comunicación para fortalecer el posicionamiento de marca.'
                ]
            ],
            'Contratos' => [
                'Legal' => [
                    'title' => "Revisar implicaciones legales de: {$title}",
                    'description' => $title ?: 'Desde la perspectiva de contratos y cumplimiento legal mitiga riesgos jurídicos.'
                ]
            ],
            'Operaciones' => [
                'Soldadura' => [
                    'title' => "Optimizar procesos de soldadura considerando: {$title}",
                    'description' => $title ?: 'Mejora la calidad y eficiencia en los procesos de soldadura.'
                ],
                'Mantenimiento' => [
                    'title' => "Mejorar mantenimiento basado en: {$title}",
                    'description' => $title ?: 'Optimiza los procesos de mantenimiento industrial.'
                ]
            ],
            'QHSE' => [
                'Calidad' => [
                    'title' => "Implementar estándar de calidad: {$title}",
                    'description' => $title ?: 'Fortalece los sistemas de calidad, seguridad y medio ambiente.'
                ]
            ],
            'Servicios Generales y Almacén' => [
                'Servicios Generales' => [
                    'title' => "Mejorar servicios considerando: {$title}",
                    'description' => $title ?: 'Optimiza la gestión de servicios generales y operaciones de almacén.'
                ]
            ]
        ];

        // Template por defecto
        $defaultTemplate = [
            'title' => "Considerar implementación de: {$title}",
            'description' => $title ?: "Aplicable en el área de {$subArea} para mejorar los procesos organizacionales."
        ];

        // Buscar template específico por departamento y sub-área
        if (isset($templates[$department]) && is_array($templates[$department])) {
            if (isset($templates[$department][$subArea]) && is_array($templates[$department][$subArea])) {
                $template = $templates[$department][$subArea];
            } else {
                // Usar el primer template del departamento
                $departmentTemplates = $templates[$department];
                $firstTemplate = reset($departmentTemplates);
                $template = is_array($firstTemplate) ? $firstTemplate : $defaultTemplate;
            }
        } else {
            $template = $defaultTemplate;
        }
        
        return [
            'title' => $template['title'],
            'description' => $template['description']
        ];
    }

    /**
     * Obtener estadísticas del scraping
     */
    public function getScrapingStats(): array
    {
        return [
            'total_scraped_recommendations' => Recommendation::where('is_scraped', true)->count(),
            'today_scraped' => Recommendation::where('is_scraped', true)
                ->whereDate('scraped_at', today())
                ->count(),
            'last_scraping' => Recommendation::where('is_scraped', true)
                ->latest('scraped_at')
                ->value('scraped_at'),
            'by_department' => Recommendation::where('is_scraped', true)
                ->with('recommendationType')
                ->get()
                ->groupBy('recommendationType.name')
                ->map(fn($group) => $group->count())
                ->toArray(),
            'by_sub_area' => Recommendation::where('is_scraped', true)
                ->whereNotNull('sub_area')
                ->selectRaw('sub_area, COUNT(*) as count')
                ->groupBy('sub_area')
                ->pluck('count', 'sub_area')
                ->toArray(),
            'by_source' => Recommendation::where('is_scraped', true)
                ->selectRaw('source, COUNT(*) as count')
                ->groupBy('source')
                ->pluck('count', 'source')
                ->toArray()
        ];
    }

    /**
     * Limpiar recomendaciones antiguas
     */
    public function cleanOldRecommendations(int $days = 60): int
    {
        $cutoffDate = now()->subDays($days);
        
        return Recommendation::where('is_scraped', true)
            ->where('created_at', '<', $cutoffDate)
            ->delete();
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