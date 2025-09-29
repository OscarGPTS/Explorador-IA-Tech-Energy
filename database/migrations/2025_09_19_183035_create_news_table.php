<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable(); // Cambiado a TEXT para soportar más contenido
            $table->longText('content')->nullable(); // Contenido completo del artículo
            $table->string('image', 255)->nullable();
            $table->string('external_link')->nullable(); // URL original de la noticia
            $table->string('image_url')->nullable(); // URL de imagen original
            $table->string('source')->nullable(); // Fuente del scraping
            $table->boolean('is_scraped')->default(false); // Si fue obtenida por scraping
            $table->timestamp('scraped_at')->nullable(); // Cuándo fue scrapeada
            $table->unsignedBigInteger('news_type_id'); // renombrado para consistencia
            $table->timestamps();

            $table->foreign('news_type_id')->references('id')->on('news_type')->onDelete('cascade');
            
            // Índices para mejorar el rendimiento
            $table->index('is_scraped');
            $table->index('source');
            $table->index('scraped_at');
            $table->index(['is_scraped', 'created_at']);
        });

        Schema::create('user_news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('news_type_id'); // se vincula al tipo de noticia
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('news_type_id')->references('id')->on('news_type')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_news');
        Schema::dropIfExists('news');
        Schema::dropIfExists('news_type');
    }
};
