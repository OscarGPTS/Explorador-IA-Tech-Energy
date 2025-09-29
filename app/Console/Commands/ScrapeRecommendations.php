<?php

namespace App\Console\Commands;

use App\Services\OptimizedRecommendationScrapingService;
use App\Services\ExtendedRecommendationScrapingService;
use Illuminate\Console\Command;

class ScrapeRecommendations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'recommendations:scrape 
                           {--strategy=mixed : Scraping strategy (working_only, with_alternatives, by_department, mixed, extended, priority)}
                           {--department= : Specific department for by_department strategy}
                           {--clean-old : Clean old recommendations (older than 60 days)}
                           {--stats : Show scraping statistics}';

    /**
     * The console command description.
     */
    protected $description = 'Scrape recommendations using optimized strategies with extended sources for low-data categories';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Iniciando Advanced Recommendation Scraping System');
        $this->newLine();

        $strategy = $this->option('strategy');
        
        // Determinar qué servicio usar
        if (in_array($strategy, ['extended', 'priority'])) {
            return $this->handleExtendedScraping($strategy);
        } else {
            return $this->handleOptimizedScraping($strategy);
        }
    }

    /**
     * Manejar scraping con fuentes extendidas
     */
    private function handleExtendedScraping(string $strategy): int
    {
        $extendedService = new ExtendedRecommendationScrapingService();
        
        // Mostrar estadísticas si se solicita
        if ($this->option('stats')) {
            $this->showStats($extendedService);
            return Command::SUCCESS;
        }

        // Limpiar recomendaciones antiguas si se solicita
        if ($this->option('clean-old')) {
            $this->cleanOldRecommendations($extendedService);
        }

        $this->displayExtendedStrategyInfo($strategy);
        
        $startTime = microtime(true);
        
        if ($strategy === 'priority') {
            $results = $extendedService->scrapeLowDataCategories();
        } else {
            $results = $extendedService->scrapeExtended();
        }
        
        $endTime = microtime(true);

        $this->displayResults($results, $endTime - $startTime);

        return Command::SUCCESS;
    }

    /**
     * Manejar scraping optimizado tradicional
     */
    private function handleOptimizedScraping(string $strategy): int
    {
        $optimizedService = new OptimizedRecommendationScrapingService();
        
        // Mostrar estadísticas si se solicita
        if ($this->option('stats')) {
            $this->showStats($optimizedService);
            return Command::SUCCESS;
        }

        // Limpiar recomendaciones antiguas si se solicita
        if ($this->option('clean-old')) {
            $this->cleanOldRecommendations($optimizedService);
        }

        $this->displayStrategyInfo($strategy);
        
        $startTime = microtime(true);
        $results = $optimizedService->scrapeOptimized($strategy);
        $endTime = microtime(true);

        $this->displayResults($results, $endTime - $startTime);

        return Command::SUCCESS;
    }

    /**
     * Mostrar información sobre estrategias extendidas
     */
    private function displayExtendedStrategyInfo(string $strategy): void
    {
        $strategies = [
            'extended' => [
                'name' => 'Fuentes Extendidas Completas',
                'description' => 'Usa 48+ nuevas fuentes verificadas para todas las categorías, especialmente QHSE, Operaciones, Ingeniería',
                'emoji' => '🌟'
            ],
            'priority' => [
                'name' => 'Prioridad para Categorías con Poca Data',
                'description' => 'Enfoque específico en QHSE (0), Operaciones (2), Ingeniería (3) y Contratos (4) con fuentes especializadas',
                'emoji' => '🎯'
            ]
        ];

        $strategyInfo = $strategies[$strategy];
        
        $this->info("📋 Estrategia Seleccionada: {$strategyInfo['emoji']} {$strategyInfo['name']}");
        $this->line("   {$strategyInfo['description']}");
        $this->newLine();
    }

    /**
     * Mostrar información sobre la estrategia seleccionada
     */
    private function displayStrategyInfo(string $strategy): void
    {
        $strategies = [
            'working_only' => [
                'name' => 'Solo URLs Verificadas',
                'description' => 'Usa únicamente las 13 URLs que funcionan correctamente (39.4% del total)',
                'emoji' => '✅'
            ],
            'with_alternatives' => [
                'name' => 'URLs Verificadas + Alternativas Internacionales',
                'description' => 'Combina URLs locales funcionales con fuentes internacionales confiables',
                'emoji' => '🌍'
            ],
            'by_department' => [
                'name' => 'Procesamiento por Departamento',
                'description' => 'Procesa departamentos uno por uno con pausas para mejor control',
                'emoji' => '🏢'
            ],
            'mixed' => [
                'name' => 'Estrategia Mixta (Recomendado)',
                'description' => 'Combina alta prioridad para URLs verificadas y alternativas para departamentos con pocas fuentes',
                'emoji' => '🎲'
            ]
        ];

        $strategyInfo = $strategies[$strategy] ?? $strategies['mixed'];
        
        $this->info("📋 Estrategia Seleccionada: {$strategyInfo['emoji']} {$strategyInfo['name']}");
        $this->line("   {$strategyInfo['description']}");
        $this->newLine();
    }

    /**
     * Mostrar resultados del scraping
     */
    private function displayResults(array $results, float $duration): void
    {
        $this->newLine();
        $this->info('📊 OPTIMIZED SCRAPING RESULTS');
        $this->info('=============================');
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Strategy Used', $results['strategy']],
                ['Total Recommendations', $results['total']],
                ['Successfully Saved', $results['success']],
                ['Errors', $results['errors']],
                ['Success Rate', $results['total'] > 0 ? round(($results['success'] / $results['total']) * 100, 2) . '%' : '0%'],
                ['Duration', round($duration, 2) . ' seconds'],
                ['Avg per Recommendation', $results['success'] > 0 ? round($duration / $results['success'], 2) . ' sec' : 'N/A']
            ]
        );

        if (!empty($results['details'])) {
            $this->newLine();
            $this->info('📈 BY DEPARTMENT:');
            
            $tableData = [];
            foreach ($results['details'] as $department => $departmentInfo) {
                $status = $departmentInfo['success'] > 0 ? '✅' : ($departmentInfo['errors'] > 0 ? '❌' : '⚪');
                $successRate = $departmentInfo['total'] > 0 ? round(($departmentInfo['success'] / $departmentInfo['total']) * 100, 1) : 0;
                
                $tableData[] = [
                    $status . ' ' . $department,
                    $departmentInfo['success'] . '/' . $departmentInfo['total'],
                    $successRate . '%'
                ];
            }
            
            if (!empty($tableData)) {
                $this->table(['Department', 'Success/Total', 'Rate'], $tableData);
            }
        }

        // Recomendaciones basadas en resultados
        $this->newLine();
        $this->info('💡 RECOMMENDATIONS:');
        
        if ($results['success'] > 0) {
            $this->line('  ✅ Scraping completed successfully!');
            $this->line('  📊 Check your database for new recommendations');
            
            if ($results['errors'] > 0) {
                $errorRate = round(($results['errors'] / $results['total']) * 100, 1);
                $this->line("  ⚠️  {$errorRate}% error rate - consider using 'working_only' strategy");
            }
        } else {
            $this->warn('  ❌ No recommendations were created');
            $this->line('  🔍 Check logs for detailed error information');
            $this->line('  💡 Try different strategy or check network connectivity');
        }

        if ($results['errors'] > 0) {
            $this->newLine();
            $this->warn("⚠️  {$results['errors']} errors occurred. Check logs for details.");
        }
    }

    /**
     * Mostrar estadísticas avanzadas
     */
    private function showStats(OptimizedRecommendationScrapingService $scrapingService): void
    {
        $stats = $scrapingService->getScrapingStats();
        
        $this->info('📊 OPTIMIZED RECOMMENDATION SCRAPING STATISTICS');
        $this->info('===============================================');
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Scraped Recommendations', $stats['total_scraped_recommendations']],
                ['Today\'s Scraped', $stats['today_scraped']],
                ['Last Scraping', $stats['last_scraping'] ? $stats['last_scraping']->format('Y-m-d H:i:s') : 'Never']
            ]
        );

        if (!empty($stats['by_department'])) {
            $this->newLine();
            $this->info('📈 BY DEPARTMENT:');
            $departmentData = [];
            foreach ($stats['by_department'] as $department => $count) {
                $departmentData[] = [$department, $count];
            }
            $this->table(['Department', 'Count'], $departmentData);
        }

        if (!empty($stats['by_sub_area'])) {
            $this->newLine();
            $this->info('🎯 BY SUB-AREA:');
            $subAreaData = [];
            arsort($stats['by_sub_area']); // Ordenar por count descendente
            foreach (array_slice($stats['by_sub_area'], 0, 10) as $subArea => $count) {
                $subAreaData[] = [$subArea, $count];
            }
            $this->table(['Sub-Area', 'Count'], $subAreaData);
        }

        if (!empty($stats['by_source'])) {
            $this->newLine();
            $this->info('🔗 BY SOURCE:');
            $sourceData = [];
            arsort($stats['by_source']); // Ordenar por count descendente
            foreach (array_slice($stats['by_source'], 0, 10) as $source => $count) {
                $sourceData[] = [$source, $count];
            }
            $this->table(['Source', 'Count'], $sourceData);
        }
    }

    /**
     * Limpiar recomendaciones antiguas
     */
    private function cleanOldRecommendations(OptimizedRecommendationScrapingService $scrapingService): void
    {
        $this->info('🧹 Cleaning old recommendations (60+ days)...');
        
        $deleted = $scrapingService->cleanOldRecommendations();
        
        if ($deleted > 0) {
            $this->info("✅ Deleted {$deleted} old recommendations");
        } else {
            $this->info('ℹ️  No old recommendations to clean');
        }
        
        $this->newLine();
    }
}
