<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\NewsType;
use App\Models\ScrapingSource;
use Illuminate\Database\Seeder;

class OilGasNewsSeeder extends Seeder
{
    /**
     * Reestructura las NOTICIAS para GPT Services (Oil & Gas):
     *  - Crea categorías temáticas del sector.
     *  - Borra noticias scrapeadas genéricas y fuentes anteriores.
     *  - Añade fuentes RSS reales de medios Oil & Gas (MX e internacionales)
     *    + consultas Google News del sector.
     *  - Limpia tipos de noticias sin contenido.
     */
    public function run(): void
    {
        $gnews = 'https://news.google.com/rss/search?q=%s&hl=es-419&gl=MX&ceid=MX:es-419';

        // Categorías temáticas Oil & Gas (tabs / preferencias de /news).
        $categories = [
            'Mercado Energético'      => 'Precios del crudo, gas natural, mercados y coyuntura energética.',
            'Pemex e Hidrocarburos'   => 'Pemex, política energética y sector de hidrocarburos en México.',
            'Exploración y Producción'=> 'Upstream: exploración, perforación, offshore y producción.',
            'Tecnología y Energía'    => 'Innovación, transición energética y tecnología para el sector.',
            'Internacional Oil & Gas' => 'Noticias globales de la industria petrolera y de gas.',
        ];

        $typeIds = [];
        foreach ($categories as $name => $desc) {
            $type = NewsType::updateOrCreate(['name' => $name], ['description' => $desc]);
            $typeIds[$name] = $type->id;
        }

        // Borrar noticias scrapeadas genéricas (conserva las manuales) y fuentes previas.
        $deleted = News::where('is_scraped', true)->delete();
        ScrapingSource::where('module', 'news')->delete();

        // Fuentes: [nombre, categoría, feed_type, url].
        $sources = [
            // --- Medios Oil & Gas reales (RSS verificado) ---
            ['Energía a Debate (MX)',  'Pemex e Hidrocarburos',    'rss', 'https://energiaadebate.com/feed/'],
            ['Global Energy (MX)',     'Pemex e Hidrocarburos',    'rss', 'https://globalenergy.mx/feed/'],
            ['Petroquimex (MX)',       'Tecnología y Energía',     'rss', 'https://petroquimex.com/feed/'],
            ['OilPrice',               'Mercado Energético',       'rss', 'https://oilprice.com/rss/main'],
            ['World Oil',              'Mercado Energético',       'rss', 'https://www.worldoil.com/rss?feed=news'],
            ['Rigzone',                'Exploración y Producción', 'rss', 'https://www.rigzone.com/news/rss/rigzone_latest.aspx'],
            ['Offshore Technology',    'Exploración y Producción', 'rss', 'https://www.offshore-technology.com/feed/'],

            // --- Google News (estable, enfoque México y sector) ---
            ['Google News · Pemex e Hidrocarburos', 'Pemex e Hidrocarburos', 'rss', sprintf($gnews, rawurlencode('Pemex hidrocarburos energía México'))],
            ['Google News · Mercado Energético',    'Mercado Energético',    'rss', sprintf($gnews, rawurlencode('precio petróleo gas natural mercado energético'))],
            ['Google News · Tecnología y Energía',  'Tecnología y Energía',  'rss', sprintf($gnews, rawurlencode('transición energética tecnología petróleo gas'))],
            ['Google News · Internacional Oil & Gas','Internacional Oil & Gas','rss', sprintf($gnews, rawurlencode('industria petrolera Oil Gas internacional'))],
        ];

        foreach ($sources as [$name, $cat, $feed, $url]) {
            ScrapingSource::create([
                'name'      => $name,
                'module'    => 'news',
                'feed_type' => $feed,
                'url'       => $url,
                'type_id'   => $typeIds[$cat],
                'max_items' => 10,
                'is_active' => true,
            ]);
        }

        // Limpiar tipos de noticias por departamento que quedaron sin contenido.
        NewsType::whereNotIn('name', array_keys($categories))
            ->whereDoesntHave('news')
            ->delete();

        $this->command?->info("Reestructura Oil & Gas de noticias aplicada. Scrapeadas borradas: {$deleted}.");
        $this->command?->line('Ejecuta: php artisan scraping:run news --sync');
    }
}
