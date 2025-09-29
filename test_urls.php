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

// URLs del sistema original de recomendaciones organizadas por departamento
$departmentUrls = [
    'Administración y Finanzas' => [
        'Recursos Humanos' => [
            'https://www.forbes.com.mx/categoria/capital-humano/',
            'https://www.elfinanciero.com.mx/empresas/',
            'https://expansion.mx/categoria/carrera',
            'https://www.entrepreneur.com/topic/recursos-humanos',
            'https://www.gestiopolis.com/categoria/talento-humano/'
        ],
        'Finanzas' => [
            'https://www.forbes.com.mx/categoria/finanzas/',
            'https://www.elfinanciero.com.mx/mercados/',
            'https://expansion.mx/categoria/finanzas',
            'https://www.banxico.org.mx/publicaciones-y-prensa/',
            'https://www.cnbv.gob.mx/CNBV/Paginas/Comunicados.aspx'
        ]
    ],
    'Contratos' => [
        'General' => [
            'https://www.gob.mx/compranet',
            'https://www.diputados.gob.mx/LeyesBiblio/',
            'https://www.scjn.gob.mx/'
        ]
    ],
    'Dirección General' => [
        'Dirección General' => [
            'https://www.forbes.com.mx/',
            'https://www.elfinanciero.com.mx/',
            'https://expansion.mx/'
        ],
        'Marketing' => [
            'https://www.merca20.com/',
            'https://www.forbes.com.mx/categoria/marketing-y-publicidad/',
            'https://expansion.mx/categoria/empresas',
            'https://www.entrepreneur.com/topic/marketing',
            'https://www.adlatina.com/advertising'
        ]
    ],
    'Ingeniería y Manufactura' => [
        'Manufactura' => [
            'https://www.manufactura.mx/'
        ],
        'Ingeniería' => [
            'https://www.industriamexicana.mx/'
        ]
    ],
    'Operaciones' => [
        'Soldadura' => [
            'https://www.aws.org/publications/wj/'
        ],
        'HT & LS' => [
            'https://www.mantenimientocorrectivo.com/'
        ],
        'Mantenimiento Especializado' => [
            'https://www.industriamexicana.mx/mantenimiento'
        ]
    ],
    'QHSE' => [
        'QHSE' => [
            'https://www.iso.org/news.html'
        ],
        'Calidad' => [
            'https://www.seguridadindustrial.com.mx/'
        ]
    ],
    'Servicios Generales y Almacén' => [
        'Logística' => [
            'https://www.logisticahoy.com.mx/'
        ],
        'Servicios Generales' => [
            'https://www.scmexico.com/'
        ],
        'IT' => [
            'https://www.cio.com.mx/'
        ],
        'Almacén' => [
            'https://www.logisticahoy.com.mx/'
        ],
        'Seguridad Patrimonial' => [
            'https://www.seguridadempresarial.com.mx/'
        ]
    ]
];

// URLs alternativas confiables por categoría
$alternativeUrls = [
    'Administración y Finanzas' => [
        'https://www.forbes.com/business/',
        'https://www.bbc.com/news/business',
        'https://finance.yahoo.com/news/',
        'https://www.cnbc.com/finance/',
        'https://www.bloomberg.com/markets'
    ],
    'Contratos' => [
        'https://www.reuters.com/legal/',
        'https://www.law.com/',
        'https://www.lexology.com/library/',
    ],
    'Dirección General' => [
        'https://www.forbes.com/',
        'https://www.bbc.com/news/business',
        'https://www.reuters.com/business/',
        'https://www.cnbc.com/business/',
        'https://hbr.org/'
    ],
    'Ingeniería y Manufactura' => [
        'https://www.manufacturingnews.com/',
        'https://www.industryweek.com/',
        'https://www.engineeringnews.co.za/',
        'https://www.automationworld.com/'
    ],
    'Operaciones' => [
        'https://www.plantengineering.com/',
        'https://www.reliableplant.com/',
        'https://www.maintenancetechnology.com/'
    ],
    'QHSE' => [
        'https://www.iso.org/news.html',
        'https://www.osha.gov/news/',
        'https://www.safetyandhealthmagazine.com/'
    ],
    'Servicios Generales y Almacén' => [
        'https://www.supplychainbrain.com/',
        'https://www.logisticsmgmt.com/',
        'https://www.mhlnews.com/'
    ]
];

echo "🔍 Probando URLs del sistema de recomendaciones...\n";
echo "=" . str_repeat("=", 60) . "\n\n";

$results = [
    'working' => [],
    'failed' => [],
    'stats' => []
];

foreach ($departmentUrls as $department => $subAreas) {
    echo "🏢 DEPARTAMENTO: {$department}\n";
    echo str_repeat("-", 50) . "\n";
    
    $departmentStats = ['working' => 0, 'failed' => 0];
    
    foreach ($subAreas as $subArea => $urls) {
        echo "  📂 {$subArea}:\n";
        
        foreach ($urls as $url) {
            echo "    🔗 Testing: {$url} ... ";
            
            try {
                $response = $client->get($url);
                $statusCode = $response->getStatusCode();
                $contentLength = strlen($response->getBody());
                
                if ($statusCode >= 200 && $statusCode < 300 && $contentLength > 1000) {
                    echo "✅ OK ({$statusCode}, {$contentLength} bytes)\n";
                    $results['working'][] = [
                        'department' => $department,
                        'sub_area' => $subArea,
                        'url' => $url,
                        'status' => $statusCode,
                        'size' => $contentLength
                    ];
                    $departmentStats['working']++;
                } else {
                    echo "⚠️  Weak ({$statusCode}, {$contentLength} bytes)\n";
                    $results['failed'][] = [
                        'department' => $department,
                        'sub_area' => $subArea,
                        'url' => $url,
                        'reason' => "Status: {$statusCode}, Size: {$contentLength}",
                        'type' => 'weak_response'
                    ];
                    $departmentStats['failed']++;
                }
                
            } catch (RequestException $e) {
                $error = $e->getMessage();
                echo "❌ FAILED ({$error})\n";
                $results['failed'][] = [
                    'department' => $department,
                    'sub_area' => $subArea,
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
                    'sub_area' => $subArea,
                    'url' => $url,
                    'reason' => $error,
                    'type' => 'general_error'
                ];
                $departmentStats['failed']++;
            }
            
            // Pequeña pausa para no sobrecargar los servidores
            usleep(500000); // 0.5 segundos
        }
    }
    
    $results['stats'][$department] = $departmentStats;
    $total = $departmentStats['working'] + $departmentStats['failed'];
    $percentage = $total > 0 ? round(($departmentStats['working'] / $total) * 100, 1) : 0;
    
    echo "  📊 Resultado: {$departmentStats['working']}/{$total} URLs funcionando ({$percentage}%)\n\n";
}

// Resumen final
echo "\n" . str_repeat("=", 70) . "\n";
echo "📊 RESUMEN FINAL\n";
echo str_repeat("=", 70) . "\n";

$totalWorking = count($results['working']);
$totalFailed = count($results['failed']);
$totalUrls = $totalWorking + $totalFailed;

echo "Total URLs probadas: {$totalUrls}\n";
echo "URLs funcionando: {$totalWorking}\n";
echo "URLs fallidas: {$totalFailed}\n";
echo "Tasa de éxito: " . ($totalUrls > 0 ? round(($totalWorking / $totalUrls) * 100, 1) : 0) . "%\n\n";

echo "📈 POR DEPARTAMENTO:\n";
foreach ($results['stats'] as $dept => $stats) {
    $total = $stats['working'] + $stats['failed'];
    $percentage = $total > 0 ? round(($stats['working'] / $total) * 100, 1) : 0;
    echo "  {$dept}: {$stats['working']}/{$total} ({$percentage}%)\n";
}

echo "\n✅ URLs QUE FUNCIONAN:\n";
foreach ($results['working'] as $working) {
    echo "  ✓ {$working['department']} / {$working['sub_area']}: {$working['url']}\n";
}

echo "\n❌ URLs CON PROBLEMAS:\n";
foreach ($results['failed'] as $failed) {
    echo "  ✗ {$failed['department']} / {$failed['sub_area']}: {$failed['url']}\n";
    echo "    Razón: {$failed['reason']}\n";
}

echo "\n💡 RECOMENDACIONES:\n";
echo "1. Usar solo las URLs que funcionan para producción\n";
echo "2. Considerar URLs alternativas para departamentos con pocas fuentes\n";
echo "3. Implementar monitoreo automático de URLs\n";
echo "4. Procesar departamentos por separado para mejor control\n";

// Guardar resultados en archivo JSON para análisis posterior
file_put_contents('url_test_results.json', json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "\n💾 Resultados guardados en: url_test_results.json\n";