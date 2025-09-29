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

        Schema::create('recommendations_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable(); // Cambiado a TEXT para más contenido
            $table->longText('content')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('external_link')->nullable(); // URL original del artículo
            $table->string('image_url')->nullable(); // URL de imagen original
            $table->string('source')->nullable(); // Fuente del scraping (dominio)
            $table->string('sub_area')->nullable(); // Sub-área específica (RH, Finanzas, etc.)
            $table->boolean('is_scraped')->default(false); // Si fue obtenida por scraping
            $table->timestamp('scraped_at')->nullable(); // Cuándo fue scrapeada
            $table->unsignedBigInteger('recommendation_type_id');
            $table->timestamps();

            $table->foreign('recommendation_type_id')->references('id')->on('recommendations_type')->onDelete('cascade');
            
            // Índices para mejorar el rendimiento
            $table->index('is_scraped');
            $table->index('source');
            $table->index('sub_area');
            $table->index('scraped_at');
            $table->index(['is_scraped', 'created_at']);
        });
        
        Schema::create('user_recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('recommendation_type_id');
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recommendation_type_id')->references('id')->on('recommendations_type')->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_recommendations');
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('recommendations_type');

    }
};
