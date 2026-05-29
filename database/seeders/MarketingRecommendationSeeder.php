<?php

namespace Database\Seeders;

use App\Models\Recommendation;
use App\Models\RecommendationType;
use Illuminate\Database\Seeder;

class MarketingRecommendationSeeder extends Seeder
{
    /**
     * Migra a la base de datos la recomendación que antes estaba hardcodeada
     * en la vista (Congreso Mexicano del Petróleo · Marketing).
     */
    public function run(): void
    {
        // Área "Dirección General" contiene la sub-área Marketing (ver RecommendationSeeder).
        $type = RecommendationType::firstOrCreate(
            ['name' => 'Dirección General'],
            ['description' => 'Recomendaciones para el área de Dirección General']
        );

        Recommendation::updateOrCreate(
            ['title' => 'Congreso Mexicano del Petróleo'],
            [
                'description' => 'Encuentro estratégico de la industria petrolera nacional en el WTC, Boca del Río, Veracruz.',
                'content' => "03 al 06 de junio de 2026 — WTC Boca del Río, Veracruz.\n\n" .
                    'El Congreso Mexicano del Petróleo se ha consolidado como uno de los encuentros más relevantes de la ' .
                    'industria energética y del sector Oil & Gas en México y Latinoamérica, reuniendo a especialistas, ' .
                    'empresas, instituciones y líderes del sector para impulsar el intercambio de conocimiento, innovación y ' .
                    "desarrollo tecnológico.\n\n" .
                    'El evento contempla un amplio programa de conferencias técnicas, paneles especializados y una destacada ' .
                    'exposición industrial en la que participan compañías nacionales e internacionales enfocadas en exploración, ' .
                    "producción, mantenimiento, infraestructura y soluciones tecnológicas para la industria petrolera.\n\n" .
                    'Además de promover la actualización técnica y científica, el CMP representa una plataforma estratégica ' .
                    'para fortalecer alianzas, generar oportunidades de negocio y fomentar la colaboración entre empresas, ' .
                    'expertos y organismos vinculados al desarrollo energético del país.',
                'image' => 'congreso_petroleo.jpg',
                'sub_area' => 'Marketing',
                'recommendation_type_id' => $type->id,
                'is_scraped' => false,
            ]
        );

        $this->command?->info('Recomendación del Congreso Mexicano del Petróleo migrada a BD.');
    }
}
