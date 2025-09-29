<?php

namespace App\Services;

use App\Models\Recommendation;
use App\Models\RecommendationType;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExtendedRecommendationScrapingService extends OptimizedRecommendationScrapingService
{
    /**
     * Nuevas fuentes verificadas para categorías con poca data
     */
    private function getExtendedUrls(): array
    {
        return [
            // Mantener URLs originales que funcionan
            'Administración y Finanzas' => [
                'Recursos Humanos' => [
                    [
                        'url' => 'https://www.forbes.com.mx/categoria/capital-humano/',
                        'name' => 'Forbes Capital Humano',
                        'selector' => '//h2[@class="title"] | //h3[@class="title"] | //h2[contains(@class,"entry-title")]'
                    ],
                    [
                        'url' => 'https://www.gestiopolis.com/categoria/talento-humano/',
                        'name' => 'Gestiopolis Talento Humano',
                        'selector' => '//h2[contains(@class,"entry-title")] | //h3'
                    ],
                    // Nuevas fuentes HR
                    [
                        'url' => 'https://www.workforce.com/',
                        'name' => 'Workforce Magazine',
                        'selector' => '//h2 | //h3 | //h1[contains(@class,"headline")]'
                    ],
                    [
                        'url' => 'https://www.peoplemanagement.co.uk/',
                        'name' => 'People Management',
                        'selector' => '//h2 | //h3 | //h1'
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
                    ],
                    // Nuevas fuentes Finance
                    [
                        'url' => 'https://www.investopedia.com/news/',
                        'name' => 'Investopedia News',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://finance.yahoo.com/news/',
                        'name' => 'Yahoo Finance',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.cnbc.com/finance/',
                        'name' => 'CNBC Finance',
                        'selector' => '//h2 | //h3 | //h1'
                    ]
                ]
            ],
            'QHSE' => [
                'Calidad y Seguridad' => [
                    [
                        'url' => 'https://www.iso.org/news.html',
                        'name' => 'ISO News',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    // Nuevas fuentes QHSE - Alta prioridad
                    [
                        'url' => 'https://www.safetyandhealthmagazine.com/',
                        'name' => 'Safety and Health Magazine',
                        'selector' => '//h2 | //h3 | //h1[contains(@class,"headline")]'
                    ],
                    [
                        'url' => 'https://www.qualitymag.com/',
                        'name' => 'Quality Magazine',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://ehstoday.com/',
                        'name' => 'EHS Today',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.qualitydigest.com/',
                        'name' => 'Quality Digest',
                        'selector' => '//h2 | //h3 | //h1'
                    ]
                ],
                'Medio Ambiente' => [
                    [
                        'url' => 'https://www.greenbiz.com/',
                        'name' => 'GreenBiz',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.sustainability-times.com/',
                        'name' => 'Sustainability Times',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.epa.gov/newsroom',
                        'name' => 'EPA Newsroom',
                        'selector' => '//h2 | //h3 | //h1'
                    ]
                ]
            ],
            'Operaciones' => [
                'Mantenimiento' => [
                    [
                        'url' => 'https://www.aws.org/publications/wj/',
                        'name' => 'AWS Welding Journal',
                        'selector' => '//h2 | //h3'
                    ],
                    // Nuevas fuentes Operaciones - Alta prioridad
                    [
                        'url' => 'https://www.maintenancetechnology.com/',
                        'name' => 'Maintenance Technology',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.reliableplant.com/',
                        'name' => 'Reliable Plant',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.plantservices.com/',
                        'name' => 'Plant Services',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.maintenanceworld.com/',
                        'name' => 'Maintenance World',
                        'selector' => '//h2 | //h3 | //h1'
                    ]
                ],
                'Automatización' => [
                    [
                        'url' => 'https://www.automationworld.com/',
                        'name' => 'Automation World',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.controlglobal.com/',
                        'name' => 'Control Global',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.manufacturing.net/',
                        'name' => 'Manufacturing.net',
                        'selector' => '//h2 | //h3 | //h1'
                    ]
                ]
            ],
            'Ingeniería y Manufactura' => [
                'Ingeniería' => [
                    // Nuevas fuentes Ingeniería - Alta prioridad
                    [
                        'url' => 'https://www.machinedesign.com/',
                        'name' => 'Machine Design',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.asme.org/topics-resources/society-news',
                        'name' => 'ASME News',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.engineeringforchange.org/',
                        'name' => 'Engineering for Change',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.newequipment.com/',
                        'name' => 'New Equipment',
                        'selector' => '//h2 | //h3 | //h1'
                    ]
                ],
                'Manufactura' => [
                    [
                        'url' => 'https://www.industryweek.com/',
                        'name' => 'Industry Week',
                        'selector' => '//h2 | //h3'
                    ],
                    [
                        'url' => 'https://www.manufacturingtomorrow.com/',
                        'name' => 'Manufacturing Tomorrow',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.productionmachining.com/',
                        'name' => 'Production Machining',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.moldmakingtechnology.com/',
                        'name' => 'Moldmaking Technology',
                        'selector' => '//h2 | //h3 | //h1'
                    ]
                ]
            ],
            'Contratos' => [
                'Legal' => [
                    [
                        'url' => 'https://www.scjn.gob.mx/',
                        'name' => 'Suprema Corte',
                        'selector' => '//h2 | //h3'
                    ],
                    // Nuevas fuentes Contratos - Alta prioridad
                    [
                        'url' => 'https://www.jdsupra.com/',
                        'name' => 'JD Supra',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.lexology.com/',
                        'name' => 'Lexology',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.law.com/',
                        'name' => 'Law.com',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://abovethelaw.com/',
                        'name' => 'Above the Law',
                        'selector' => '//h2 | //h3 | //h1'
                    ]
                ],
                'Compliance' => [
                    [
                        'url' => 'https://www.legaltech.news/',
                        'name' => 'Legal Tech News',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.businesslawtoday.org/',
                        'name' => 'Business Law Today',
                        'selector' => '//h2 | //h3 | //h1'
                    ],
                    [
                        'url' => 'https://www.law360.com/corporate',
                        'name' => 'Law360 Corporate',
                        'selector' => '//h2 | //h3 | //h1'
                    ]
                ]
            ],
            // Mantener otras categorías existentes
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
            'Servicios Generales y Almacén' => [
                'Servicios Generales' => [
                    [
                        'url' => 'https://www.scmexico.com/',
                        'name' => 'SCM México',
                        'selector' => '//h2 | //h3'
                    ]
                ],
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
     * Scrape usando las fuentes extendidas
     */
    public function scrapeExtended(array $priorityDepartments = []): array
    {
        $results = [
            'total' => 0,
            'success' => 0,
            'errors' => 0,
            'details' => [],
            'strategy' => 'extended_sources'
        ];

        Log::info('🌟 Starting Extended Recommendation Scraping');
        
        $extendedUrls = $this->getExtendedUrls();
        
        // Si se especifican departamentos prioritarios, procesarlos primero
        if (!empty($priorityDepartments)) {
            foreach ($priorityDepartments as $deptName) {
                if (isset($extendedUrls[$deptName])) {
                    $departmentResult = $this->scrapeDepartmentSources($deptName, $extendedUrls[$deptName]);
                    
                    $results['total'] += $departmentResult['total'];
                    $results['success'] += $departmentResult['success'];
                    $results['errors'] += $departmentResult['errors'];
                    $results['details'][$deptName] = $departmentResult;
                    
                    unset($extendedUrls[$deptName]); // Remover para evitar duplicados
                }
            }
        }
        
        // Procesar el resto de departamentos
        foreach ($extendedUrls as $departmentName => $subAreas) {
            $departmentResult = $this->scrapeDepartmentSources($departmentName, $subAreas);
            
            $results['total'] += $departmentResult['total'];
            $results['success'] += $departmentResult['success'];
            $results['errors'] += $departmentResult['errors'];
            $results['details'][$departmentName] = $departmentResult;
        }

        return $results;
    }

    /**
     * Scrape solo categorías con poca data (enfoque prioritario)
     */
    public function scrapeLowDataCategories(): array
    {
        Log::info('🎯 Scraping Low Data Categories Priority');
        
        $priorityDepartments = ['QHSE', 'Operaciones', 'Ingeniería y Manufactura', 'Contratos'];
        
        return $this->scrapeExtended($priorityDepartments);
    }

    /**
     * Templates de recomendaciones mejorados para nuevas categorías
     */
    protected function transformToRecommendation(string $title, string $department, string $subArea): array
    {
        $templates = [
            'QHSE' => [
                'Calidad y Seguridad' => [
                    'title' => "Implementar estándar de seguridad: {$title}",
                    'description' => $title ?: 'Fortalece los sistemas de calidad, seguridad industrial y gestión de riesgos organizacionales.'
                ],
                'Medio Ambiente' => [
                    'title' => "Aplicar práctica ambiental: {$title}",
                    'description' => $title ?: 'Mejora la gestión ambiental y sostenibilidad de los procesos organizacionales.'
                ]
            ],
            'Operaciones' => [
                'Mantenimiento' => [
                    'title' => "Optimizar mantenimiento con: {$title}",
                    'description' => $title ?: 'Mejora la eficiencia del mantenimiento preventivo y correctivo de equipos.'
                ],
                'Automatización' => [
                    'title' => "Automatizar procesos usando: {$title}",
                    'description' => $title ?: 'Automatiza operaciones y mejora la productividad industrial.'
                ]
            ],
            'Ingeniería y Manufactura' => [
                'Ingeniería' => [
                    'title' => "Aplicar innovación en ingeniería: {$title}",
                    'description' => $title ?: 'Mejora los procesos de diseño e innovación en ingeniería.'
                ],
                'Manufactura' => [
                    'title' => "Optimizar manufactura con: {$title}",
                    'description' => $title ?: 'Mejora los procesos de manufactura y producción industrial.'
                ]
            ],
            'Contratos' => [
                'Legal' => [
                    'title' => "Revisar aspecto legal: {$title}",
                    'description' => $title ?: 'Desde la perspectiva legal y contractual mitiga riesgos jurídicos.'
                ],
                'Compliance' => [
                    'title' => "Asegurar cumplimiento de: {$title}",
                    'description' => $title ?: 'Fortalece el cumplimiento normativo y gestión de compliance.'
                ]
            ]
        ];

        // Usar el template padre si no hay específico
        return parent::transformToRecommendation($title, $department, $subArea);
    }
}