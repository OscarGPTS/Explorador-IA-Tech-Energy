<?php

namespace Database\Seeders;

use App\Models\Recommendation;
use App\Models\RecommendationType;
use App\Models\ScrapingSource;
use Illuminate\Database\Seeder;

class OilGasRecommendationSeeder extends Seeder
{
    /**
     * Reestructura las recomendaciones para GPT Services (Oil & Gas) enfocándolas
     * en DESARROLLO PROFESIONAL (congresos, cursos, capacitaciones, certificaciones),
     * para diferenciarlas de /news (noticias generales del sector):
     *  - Crea las categorías temáticas (tabs).
     *  - Reapunta la recomendación manual del Congreso a "Congresos y Expos".
     *  - Elimina recomendaciones scrapeadas previas y fuentes anteriores.
     *  - Crea fuentes Google News RSS enfocadas en eventos/formación Oil & Gas.
     *  - Limpia las áreas (tipos) que queden sin contenido.
     */
    public function run(): void
    {
        $base = 'https://news.google.com/rss/search?q=%s&hl=es-419&gl=MX&ceid=MX:es-419';

        // Categorías de desarrollo profesional Oil & Gas: nombre => [descripción, query].
        $categories = [
            'Congresos y Expos' => [
                'desc'  => 'Congresos, convenciones, expos y ferias de la industria petrolera y energética.',
                'query' => 'congreso convención expo feria petróleo energía oil gas',
            ],
            'Cursos y Capacitación' => [
                'desc'  => 'Cursos, talleres y programas de capacitación técnica para el sector energético.',
                'query' => 'curso taller capacitación industria petrolera energía',
            ],
            'Certificaciones y Normas' => [
                'desc'  => 'Certificaciones profesionales (API, ASME, NACE, ISO) y normas del sector.',
                'query' => 'certificación norma seguridad industrial petróleo energía profesional',
            ],
            'Desarrollo Profesional' => [
                'desc'  => 'Programas de talento, competencias y formación especializada en Oil & Gas.',
                'query' => 'programa formación especialización competencias sector energético petróleo',
            ],
        ];

        // 1) Crear/asegurar las categorías nuevas.
        $typeIds = [];
        foreach ($categories as $name => $data) {
            $type = RecommendationType::updateOrCreate(
                ['name' => $name],
                ['description' => $data['desc']]
            );
            $typeIds[$name] = $type->id;
        }

        // 2) Reapuntar el Congreso del Petróleo (manual) a "Congresos y Expos".
        Recommendation::where('title', 'Congreso Mexicano del Petróleo')->update([
            'recommendation_type_id' => $typeIds['Congresos y Expos'],
            'sub_area' => 'Congresos y Expos',
        ]);

        // 3) Borrar recomendaciones scrapeadas genéricas (conserva las manuales).
        $deletedRecs = Recommendation::where('is_scraped', true)->delete();

        // 4) Reemplazar las fuentes de scraping del módulo recomendaciones.
        ScrapingSource::where('module', 'recommendations')->delete();
        foreach ($categories as $name => $data) {
            ScrapingSource::create([
                'name'      => "Oil & Gas · {$name}",
                'module'    => 'recommendations',
                'feed_type' => 'rss',
                'url'       => sprintf($base, rawurlencode($data['query'])),
                'type_id'   => $typeIds[$name],
                'sub_area'  => $name,
                'max_items' => 10,
                'is_active' => true,
            ]);
        }

        // 5) Limpiar tipos por departamento que ya no tienen recomendaciones.
        RecommendationType::whereNotIn('name', array_keys($categories))
            ->whereDoesntHave('recommendations')
            ->delete();

        $this->command?->info("Reestructura Oil & Gas aplicada. Recomendaciones scrapeadas borradas: {$deletedRecs}.");
        $this->command?->line('Ejecuta el scraping: php artisan scraping:run recommendations --sync');
    }
}
