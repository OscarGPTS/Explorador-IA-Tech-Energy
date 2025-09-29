<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Recommendation;

echo "=== VERIFICACIÓN DE RECOMENDACIONES CON CONTENIDO COMPLETO ===\n\n";

$recommendations = Recommendation::limit(3)->get();

foreach ($recommendations as $rec) {
    echo "🔹 TÍTULO: " . $rec->title . "\n";
    echo "📝 DESCRIPCIÓN: " . substr($rec->description, 0, 100) . "...\n";
    echo "📄 CONTENIDO: " . (strlen($rec->content) > 0 ? strlen($rec->content) . " caracteres" : "Sin contenido") . "\n";
    echo "🖼️  IMAGEN: " . ($rec->image_url ? "SÍ - " . $rec->image_url : "NO") . "\n";
    echo "🔗 LINK: " . $rec->external_link . "\n";
    echo "📊 FUENTE: " . $rec->source . "\n";
    echo str_repeat("-", 80) . "\n";
}

echo "\n📊 ESTADÍSTICAS:\n";
echo "Total recomendaciones: " . Recommendation::count() . "\n";
echo "Con contenido: " . Recommendation::whereNotNull('content')->where('content', '!=', '')->count() . "\n";
echo "Con imágenes: " . Recommendation::whereNotNull('image_url')->count() . "\n";

echo "\n✅ Verificación completada!\n";