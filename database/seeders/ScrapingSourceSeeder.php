<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ScrapingSourceSeeder extends Seeder
{
    /**
     * Orquesta el sembrado de fuentes de scraping enfocadas en Oil & Gas
     * (GPT Services), tanto para Noticias como para Recomendaciones.
     *
     * ADVERTENCIA: los seeders Oil & Gas borran el contenido scrapeado previo
     * y recrean las fuentes. Las noticias/recomendaciones cargadas manualmente
     * (is_scraped = false) se conservan.
     */
    public function run(): void
    {
        $this->call([
            OilGasNewsSeeder::class,
            OilGasRecommendationSeeder::class,
        ]);

        $this->command?->info('Fuentes Oil & Gas (noticias + recomendaciones) sembradas. Ejecuta: php artisan scraping:run all --sync');
    }
}
