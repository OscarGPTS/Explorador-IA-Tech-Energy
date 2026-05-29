<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scraping_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('module', ['news', 'recommendations'])->index();
            $table->enum('feed_type', ['rss', 'html'])->default('rss');
            $table->text('url');
            // Apunta a news_type.id o recommendations_type.id según el módulo.
            $table->unsignedBigInteger('type_id')->nullable();
            $table->string('sub_area')->nullable();
            // Selectores CSS/XPath para feed_type = html.
            $table->json('selectors')->nullable();
            $table->unsignedSmallInteger('max_items')->default(10);
            $table->boolean('is_active')->default(true)->index();

            // Estado de la última ejecución (panel en vivo).
            $table->timestamp('last_run_at')->nullable();
            $table->string('last_status')->default('never'); // never | ok | error
            $table->unsignedInteger('last_items')->default(0);
            $table->text('last_error')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scraping_sources');
    }
};
