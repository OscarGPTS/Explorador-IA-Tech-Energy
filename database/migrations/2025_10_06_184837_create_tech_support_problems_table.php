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
        Schema::create('tech_support_problems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tech_support_category_id')->constrained()->onDelete('cascade');
            $table->string('problem_key')->unique(); // clave única (ej: computadora_lenta)
            $table->string('title'); // título del problema (ej: Mi computadora está muy lenta)
            $table->text('description')->nullable(); // descripción breve
            $table->text('solution_title'); // título de la solución
            $table->longText('solution_content'); // contenido HTML de la solución
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('estimated_time')->nullable(); // tiempo estimado
            $table->integer('sort_order')->default(0); // orden de visualización
            $table->boolean('is_active')->default(true); // si está activo
            $table->json('keywords')->nullable(); // palabras clave para búsqueda
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_support_problems');
    }
};
