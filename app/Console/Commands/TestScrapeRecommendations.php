<?php

namespace App\Console\Commands;

use App\Services\TestRecommendationScrapingService;
use Illuminate\Console\Command;

class TestScrapeRecommendations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:scrape-recommendations 
                           {--stats : Show current statistics}
                           {--clean : Clean test data first}';

    /**
     * The console command description.
     */
    protected $description = 'Test recommendation scraping with reliable sources';

    /**
     * Execute the console command.
     */
    public function handle(TestRecommendationScrapingService $scrapingService): int
    {
        $this->info('🧪 Testing Recommendation Scraping System');
        $this->newLine();

        // Show stats if requested
        if ($this->option('stats')) {
            $this->showStats($scrapingService);
            return Command::SUCCESS;
        }

        // Clean test data if requested
        if ($this->option('clean')) {
            $this->cleanTestData();
        }

        // Run test scraping
        $this->info('🚀 Starting test scraping...');
        $startTime = microtime(true);
        
        $results = $scrapingService->scrapeTestSources();
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);

        $this->displayResults($results, $duration);

        return Command::SUCCESS;
    }

    /**
     * Display scraping results
     */
    private function displayResults(array $results, float $duration): void
    {
        $this->newLine();
        $this->info('📊 TEST SCRAPING RESULTS');
        $this->info('========================');
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Recommendations', $results['total']],
                ['Successfully Created', $results['success']],
                ['Errors', $results['errors']],
                ['Success Rate', $results['total'] > 0 ? round(($results['success'] / $results['total']) * 100, 2) . '%' : '0%'],
                ['Duration', $duration . ' seconds']
            ]
        );

        if (!empty($results['details'])) {
            $this->newLine();
            $this->info('📈 BY DEPARTMENT:');
            
            foreach ($results['details'] as $department => $data) {
                $status = $data['success'] > 0 ? '✅' : ($data['errors'] > 0 ? '❌' : '⚪');
                $this->line("  {$status} {$department}: {$data['success']}/{$data['total']} recommendations");
            }
        }

        if ($results['success'] > 0) {
            $this->newLine();
            $this->info('✅ Test scraping completed successfully!');
            $this->info('💡 You can now check the recommendations in your database.');
        } else {
            $this->newLine();
            $this->warn('⚠️  No recommendations were created. Check the logs for details.');
        }
    }

    /**
     * Show current statistics
     */
    private function showStats(TestRecommendationScrapingService $scrapingService): void
    {
        $stats = $scrapingService->getStats();
        
        $this->info('📊 RECOMMENDATION SCRAPING STATISTICS');
        $this->info('====================================');
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Scraped Recommendations', $stats['total_scraped']],
                ['Today\'s Scraped', $stats['today_scraped']]
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
    }

    /**
     * Clean test data
     */
    private function cleanTestData(): void
    {
        $this->info('🧹 Cleaning previous test data...');
        
        $deleted = \App\Models\Recommendation::where('is_scraped', true)->delete();
        
        if ($deleted > 0) {
            $this->info("✅ Deleted {$deleted} previous test recommendations");
        } else {
            $this->info('ℹ️  No previous test data to clean');
        }
        
        $this->newLine();
    }
}
