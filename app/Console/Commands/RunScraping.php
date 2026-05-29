<?php

namespace App\Console\Commands;

use App\Jobs\RunScrapingJob;
use App\Services\SourceScraperService;
use Illuminate\Console\Command;

class RunScraping extends Command
{
    protected $signature = 'scraping:run
                            {module=all : news | recommendations | all}
                            {--sync : Ejecutar de inmediato (sin cola)}';

    protected $description = 'Ejecuta el scraping de fuentes configuradas (RSS/HTML) por módulo';

    public function handle(SourceScraperService $scraper): int
    {
        $module = $this->argument('module');
        $modules = $module === 'all' ? ['news', 'recommendations'] : [$module];

        foreach ($modules as $m) {
            if (!in_array($m, ['news', 'recommendations'], true)) {
                $this->error("Módulo inválido: {$m}");
                return self::FAILURE;
            }

            if ($this->option('sync')) {
                $this->info("Scrapeando módulo '{$m}' (sincrónico)…");
                $summary = $scraper->scrapeModule($m);
                $this->table(
                    ['Fuentes', 'Items', 'OK', 'Errores'],
                    [[$summary['sources'], $summary['items'], $summary['success'], $summary['errors']]]
                );
            } else {
                RunScrapingJob::dispatch($m);
                $this->info("Job de scraping para '{$m}' encolado.");
            }
        }

        return self::SUCCESS;
    }
}
