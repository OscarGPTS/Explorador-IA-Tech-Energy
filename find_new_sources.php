<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$client = new Client([
    'timeout' => 10,
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    ]
]);

// Nuevas fuentes potenciales organizadas por categorías con poca data
$potentialSources = [
    'QHSE' => [
        'Quality & Safety' => [
            'https://www.safetyandhealthmagazine.com/',
            'https://www.occupationalhealthandsafety.com/',
            'https://www.qualitymag.com/',
            'https://www.qualitydigest.com/',
            'https://ehstoday.com/',
            'https://www.environews.tv/',
            'https://www.isobudgets.com/iso-news/',
            'https://www.bsigroup.com/en-GB/about-bsi/media-centre/',
            'https://www.tuv.com/world/en/news.html',
            'https://www.dnv.com/news/index.html'
        ],
        'Environmental' => [
            'https://www.environmentalleader.com/',
            'https://www.greenbiz.com/',
            'https://www.sustainability-times.com/',
            'https://www.epa.gov/newsroom'
        ]
    ],
    'Operaciones' => [
        'Maintenance & Operations' => [
            'https://www.reliableplant.com/',
            'https://www.plantservices.com/',
            'https://www.maintenanceworld.com/',
            'https://www.machinerylubricationindia.com/',
            'https://www.pdma.org/news/',
            'https://www.facilitiesnet.com/',
            'https://www.foodengineeringmag.com/'
        ],
        'Industrial Automation' => [
            'https://www.automationworld.com/',
            'https://www.controlglobal.com/',
            'https://www.manufacturing.net/',
            'https://www.assemblymag.com/'
        ]
    ],
    'Ingeniería y Manufactura' => [
        'Engineering' => [
            'https://www.engineering.com/',
            'https://www.machinedesign.com/',
            'https://www.designnews.com/',
            'https://www.asme.org/topics-resources/society-news',
            'https://www.engineeringforchange.org/',
            'https://www.engineersjournal.ie/',
            'https://www.newequipment.com/',
            'https://www.thomasnet.com/insights/'
        ],
        'Manufacturing' => [
            'https://www.manufacturingtomorrow.com/',
            'https://www.mmh.com/',
            'https://www.assemblymag.com/',
            'https://www.metalworkingworldmagazine.com/',
            'https://www.productionmachining.com/',
            'https://www.moldmakingtechnology.com/'
        ]
    ],
    'Contratos' => [
        'Legal & Compliance' => [
            'https://www.jdsupra.com/',
            'https://www.lexology.com/',
            'https://www.law.com/',
            'https://abovethelaw.com/',
            'https://www.americanbar.org/news/',
            'https://www.insidecounsel.com/',
            'https://www.legaltech.news/',
            'https://www.complinet.com/global/'
        ],
        'Business Law' => [
            'https://www.businesslawtoday.org/',
            'https://www.acc.com/news',
            'https://www.corporatecounsel.com/',
            'https://www.law360.com/corporate'
        ]
    ],
    'Administración y Finanzas' => [
        'Finance & Economics' => [
            'https://www.investopedia.com/news/',
            'https://www.bloomberg.com/news/',
            'https://www.ft.com/',
            'https://www.wsj.com/',
            'https://finance.yahoo.com/news/',
            'https://www.marketwatch.com/',
            'https://www.cnbc.com/finance/',
            'https://www.reuters.com/business/finance/'
        ],
        'HR & Management' => [
            'https://www.shrm.org/ResourcesAndTools/hr-topics/',
            'https://www.hrbartender.com/',
            'https://www.humanresourcestoday.com/',
            'https://www.workforce.com/',
            'https://www.hrexecutive.com/',
            'https://www.peoplemanagement.co.uk/'
        ]
    ]
];

echo "🔍 Buscando nuevas fuentes para categorías con poca data...\n";
echo "=" . str_repeat("=", 70) . "\n\n";

$results = [
    'working' => [],
    'failed' => [],
    'recommendations' => []
];

foreach ($potentialSources as $department => $categories) {
    echo "🏢 DEPARTAMENTO: {$department}\n";
    echo str_repeat("-", 60) . "\n";
    
    $departmentStats = ['working' => 0, 'failed' => 0, 'total' => 0];
    
    foreach ($categories as $category => $urls) {
        echo "  📂 Categoría: {$category}\n";
        
        foreach ($urls as $url) {
            $departmentStats['total']++;
            echo "    🔗 Testing: {$url} ... ";
            
            try {
                $response = $client->get($url);
                $statusCode = $response->getStatusCode();
                $contentLength = strlen($response->getBody());
                
                if ($statusCode >= 200 && $statusCode < 300 && $contentLength > 2000) {
                    echo "✅ OK ({$statusCode}, " . number_format($contentLength) . " bytes)\n";
                    
                    $results['working'][] = [
                        'department' => $department,
                        'category' => $category,
                        'url' => $url,
                        'status' => $statusCode,
                        'size' => $contentLength,
                        'priority' => calculatePriority($department, $contentLength)
                    ];
                    $departmentStats['working']++;
                    
                } else {
                    echo "⚠️  Weak ({$statusCode}, " . number_format($contentLength) . " bytes)\n";
                    $results['failed'][] = [
                        'department' => $department,
                        'url' => $url,
                        'reason' => "Status: {$statusCode}, Size: {$contentLength}",
                        'type' => 'weak_response'
                    ];
                    $departmentStats['failed']++;
                }
                
            } catch (RequestException $e) {
                $error = getSimpleError($e->getMessage());
                echo "❌ FAILED ({$error})\n";
                $results['failed'][] = [
                    'department' => $department,
                    'url' => $url,
                    'reason' => $error,
                    'type' => 'request_error'
                ];
                $departmentStats['failed']++;
            } catch (Exception $e) {
                $error = $e->getMessage();
                echo "❌ ERROR ({$error})\n";
                $results['failed'][] = [
                    'department' => $department,
                    'url' => $url,
                    'reason' => $error,
                    'type' => 'general_error'
                ];
                $departmentStats['failed']++;
            }
            
            // Pausa para no sobrecargar servidores
            usleep(300000); // 0.3 segundos
        }
        
        echo "\n";
    }
    
    $percentage = $departmentStats['total'] > 0 ? 
        round(($departmentStats['working'] / $departmentStats['total']) * 100, 1) : 0;
    
    echo "  📊 Resultado: {$departmentStats['working']}/{$departmentStats['total']} URLs funcionando ({$percentage}%)\n\n";
    
    // Recomendaciones específicas para este departamento
    $workingSources = array_filter($results['working'], fn($item) => $item['department'] === $department);
    if (!empty($workingSources)) {
        $results['recommendations'][$department] = generateRecommendations($department, $workingSources);
    }
}

// Función para calcular prioridad
function calculatePriority($department, $contentLength) {
    $priorities = [
        'QHSE' => 100,           // Máxima prioridad (0 recomendaciones)
        'Operaciones' => 90,     // Alta prioridad (2 recomendaciones)
        'Ingeniería y Manufactura' => 80, // Alta prioridad (3 recomendaciones)
        'Contratos' => 70,       // Media prioridad (4 recomendaciones)
        'Administración y Finanzas' => 50, // Baja prioridad (ya tiene 7)
    ];
    
    $basePriority = $priorities[$department] ?? 50;
    $sizeFactor = min($contentLength / 50000, 2); // Factor de tamaño (max 2x)
    
    return round($basePriority * $sizeFactor);
}

// Función para simplificar errores
function getSimpleError($message) {
    if (strpos($message, 'timeout') !== false) return 'Timeout';
    if (strpos($message, 'resolve host') !== false) return 'DNS Error';
    if (strpos($message, '404') !== false) return '404 Not Found';
    if (strpos($message, '403') !== false) return '403 Forbidden';
    if (strpos($message, '500') !== false) return '500 Server Error';
    if (strpos($message, 'SSL') !== false) return 'SSL Error';
    return 'Network Error';
}

// Función para generar recomendaciones por departamento
function generateRecommendations($department, $sources) {
    $recommendations = [];
    
    // Ordenar por prioridad
    usort($sources, fn($a, $b) => $b['priority'] <=> $a['priority']);
    
    $recommendations['top_sources'] = array_slice($sources, 0, 5); // Top 5 fuentes
    $recommendations['selectors'] = getSuggestedSelectors($department);
    $recommendations['implementation'] = getImplementationSuggestions($department);
    
    return $recommendations;
}

// Función para obtener selectores sugeridos
function getSuggestedSelectors($department) {
    $selectors = [
        'QHSE' => ['//h2', '//h3', '//h1[contains(@class,"title")]', '//article//h2'],
        'Operaciones' => ['//h2', '//h3', '//h1', '//article//h2', '//div[contains(@class,"title")]//h2'],
        'Ingeniería y Manufactura' => ['//h2', '//h3', '//h1', '//article//h2'],
        'Contratos' => ['//h2', '//h3', '//h1', '//article//h2', '//div[contains(@class,"headline")]'],
        'Administración y Finanzas' => ['//h2', '//h3', '//h1', '//article//h2']
    ];
    
    return $selectors[$department] ?? ['//h2', '//h3'];
}

// Función para obtener sugerencias de implementación
function getImplementationSuggestions($department) {
    return [
        'priority' => calculatePriority($department, 50000),
        'frequency' => 'daily',
        'max_articles_per_source' => 3,
        'content_filters' => [
            'min_length' => 30,
            'max_length' => 200,
            'exclude_words' => ['advertisement', 'sponsored', 'ad:', 'promo']
        ]
    ];
}

// Resumen final y recomendaciones
echo "\n" . str_repeat("=", 80) . "\n";
echo "📊 RESUMEN DE NUEVAS FUENTES ENCONTRADAS\n";
echo str_repeat("=", 80) . "\n";

$totalWorking = count($results['working']);
$totalFailed = count($results['failed']);
$totalTested = $totalWorking + $totalFailed;

echo "📈 ESTADÍSTICAS GENERALES:\n";
echo "  Total URLs probadas: {$totalTested}\n";
echo "  URLs funcionando: {$totalWorking}\n";
echo "  URLs fallidas: {$totalFailed}\n";
echo "  Tasa de éxito: " . ($totalTested > 0 ? round(($totalWorking / $totalTested) * 100, 1) : 0) . "%\n\n";

echo "🎯 FUENTES RECOMENDADAS POR DEPARTAMENTO:\n";
foreach ($results['recommendations'] as $dept => $rec) {
    echo "\n📁 {$dept}:\n";
    foreach ($rec['top_sources'] as $i => $source) {
        $rank = $i + 1;
        echo "  {$rank}. {$source['url']} (Prioridad: {$source['priority']})\n";
    }
}

echo "\n💡 PRÓXIMOS PASOS:\n";
echo "1. Implementar las fuentes de alta prioridad para QHSE y Operaciones\n";
echo "2. Configurar selectores específicos para cada tipo de contenido\n";
echo "3. Ajustar frecuencia de scraping según disponibilidad de contenido\n";
echo "4. Implementar filtros de contenido para mejorar calidad\n";

// Guardar resultados
file_put_contents('new_sources_analysis.json', json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "\n💾 Análisis completo guardado en: new_sources_analysis.json\n";