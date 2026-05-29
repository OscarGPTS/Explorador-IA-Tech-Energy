<?php

namespace App\Jobs;

use App\Models\ScrapingSource;
use App\Services\SourceScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunScrapingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;
    public int $tries = 1;

    /**
     * @param  string    $module    'news' | 'recommendations'
     * @param  int|null  $sourceId  Si se indica, solo scrapea esa fuente.
     */
    public function __construct(
        public string $module,
        public ?int $sourceId = null
    ) {}

    public function handle(SourceScraperService $scraper): void
    {
        if ($this->sourceId) {
            $source = ScrapingSource::find($this->sourceId);
            if (!$source) {
                Log::warning("RunScrapingJob: fuente #{$this->sourceId} no encontrada.");
                return;
            }
            $result = $scraper->scrapeSource($source);
            Log::info("RunScrapingJob fuente #{$source->id}: " . json_encode($result));
            return;
        }

        $summary = $scraper->scrapeModule($this->module);
        Log::info("RunScrapingJob módulo {$this->module}: " . json_encode([
            'sources' => $summary['sources'],
            'items'   => $summary['items'],
            'errors'  => $summary['errors'],
        ]));
    }
}
