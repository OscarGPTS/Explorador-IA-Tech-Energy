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
        Schema::create('tech_support_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // nombre de la categoría (ej: computadora, internet)
            $table->string('display_name'); // nombre para mostrar (ej: Computadora, Internet)
            $table->string('icon')->nullable(); // ícono para la categoría
            $table->text('description')->nullable(); // descripción de la categoría
            $table->integer('sort_order')->default(0); // orden de visualización
            $table->boolean('is_active')->default(true); // si está activa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_support_categories');
    }
};
