<?php

namespace App\Console\Commands;

use App\Services\NewsScrapingService;
use Illuminate\Console\Command;

class ScrapeNews extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'news:scrape 
                           {--source= : Specific source to scrape (eluniversal, elfinanciero, milenio)}
                           {--clean-old : Clean old news (older than 30 days)}
                           {--stats : Show scraping statistics}';

    /**
     * The console command description.
     */
    protected $description = 'Scrape news from Mexican news websites';

    /**
     * Execute the console command.
     */
    public function handle(NewsScrapingService $scrapingService): int
    {
        $this->info('🚀 Iniciando News Scraping System');
        $this->newLine();

        // Mostrar estadísticas si se solicita
        if ($this->option('stats')) {
            $this->showStats($scrapingService);
            return Command::SUCCESS;
        }

        // Limpiar noticias antiguas si se solicita
        if ($this->option('clean-old')) {
            $this->cleanOldNews($scrapingService);
        }

        // Ejecutar scraping
        $source = $this->option('source');
        
        if ($source) {
            $this->scrapeSpecificSource($scrapingService, $source);
        } else {
            $this->scrapeAllSources($scrapingService);
        }

        $this->newLine();
        $this->info('✅ News scraping completed successfully!');
        
        return Command::SUCCESS;
    }

    /**
     * Scrape todas las fuentes
     */
    private function scrapeAllSources(NewsScrapingService $scrapingService): void
    {
        $this->info('📰 Scraping all news sources...');
        $this->newLine();

        $startTime = microtime(true);
        $results = $scrapingService->scrapeAllSources();
        $endTime = microtime(true);

        $this->displayResults($results, $endTime - $startTime);
    }

    /**
     * Scrape una fuente específica
     */
    private function scrapeSpecificSource(NewsScrapingService $scrapingService, string $source): void
    {
        $validSources = ['eluniversal', 'elfinanciero', 'milenio'];
        
        if (!in_array($source, $validSources)) {
            $this->error("❌ Invalid source: {$source}");
            $this->info("Valid sources: " . implode(', ', $validSources));
            return;
        }

        $this->info("📰 Scraping {$source}...");
        $this->newLine();

        // Note: This would require modifying the service to support single source scraping
        $this->warn("⚠️  Single source scraping not yet implemented. Scraping all sources...");
        $this->scrapeAllSources($scrapingService);
    }

    /**
     * Mostrar resultados del scraping
     */
    private function displayResults(array $results, float $duration): void
    {
        $this->newLine();
        $this->info('📊 SCRAPING RESULTS');
        $this->info('==================');
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Articles', $results['total']],
                ['Successfully Saved', $results['success']],
                ['Errors', $results['errors']],
                ['Success Rate', $results['total'] > 0 ? round(($results['success'] / $results['total']) * 100, 2) . '%' : '0%'],
                ['Duration', round($duration, 2) . ' seconds']
            ]
        );

        if (!empty($results['details'])) {
            $this->newLine();
            $this->info('📈 BY SOURCE:');
            
            foreach ($results['details'] as $source => $sourceData) {
                $this->info("  🔸 {$source}: {$sourceData['success']}/{$sourceData['total']} articles");
                
                if (!empty($sourceData['sections'])) {
                    foreach ($sourceData['sections'] as $section => $sectionData) {
                        $this->line("    - {$section}: {$sectionData['success']}/{$sectionData['total']}");
                    }
                }
            }
        }

        if ($results['errors'] > 0) {
            $this->newLine();
            $this->warn("⚠️  {$results['errors']} errors occurred. Check logs for details.");
        }
    }

    /**
     * Mostrar estadísticas
     */
    private function showStats(NewsScrapingService $scrapingService): void
    {
        $stats = $scrapingService->getScrapingStats();
        
        $this->info('📊 NEWS SCRAPING STATISTICS');
        $this->info('==========================');
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Scraped News', $stats['total_scraped_news']],
                ['Today\'s Scraped', $stats['today_scraped']],
                ['Last Scraping', $stats['last_scraping'] ? $stats['last_scraping']->format('Y-m-d H:i:s') : 'Never']
            ]
        );

        if (!empty($stats['by_source'])) {
            $this->newLine();
            $this->info('📈 BY SOURCE:');
            $sourceData = [];
            foreach ($stats['by_source'] as $source => $count) {
                $sourceData[] = [$source, $count];
            }
            $this->table(['Source', 'Count'], $sourceData);
        }

        if (!empty($stats['by_type'])) {
            $this->newLine();
            $this->info('🏷️  BY NEWS TYPE:');
            $typeData = [];
            foreach ($stats['by_type'] as $type => $count) {
                $typeData[] = [$type, $count];
            }
            $this->table(['Type', 'Count'], $typeData);
        }
    }

    /**
     * Limpiar noticias antiguas
     */
    private function cleanOldNews(NewsScrapingService $scrapingService): void
    {
        $this->info('🧹 Cleaning old news (30+ days)...');
        
        $deleted = $scrapingService->cleanOldNews();
        
        if ($deleted > 0) {
            $this->info("✅ Deleted {$deleted} old news articles");
        } else {
            $this->info('ℹ️  No old news to clean');
        }
        
        $this->newLine();
    }
}