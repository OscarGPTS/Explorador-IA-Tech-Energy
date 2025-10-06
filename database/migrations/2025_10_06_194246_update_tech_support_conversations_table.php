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
        Schema::table('tech_support_conversations', function (Blueprint $table) {
            // Agregar relaciones con las nuevas tablas
            $table->foreignId('tech_support_category_id')->nullable()->after('employee_id');
            $table->foreignId('tech_support_problem_id')->nullable()->after('tech_support_category_id');
            
            // Cambiar problem_category para permitir valores dinámicos
            $table->string('problem_category_dynamic')->nullable()->after('tech_support_problem_id');
            $table->string('problem_key')->nullable()->after('problem_category_dynamic'); // Clave del problema resuelto
            
            // Agregar foreign keys con eliminación en cascada
            $table->foreign('tech_support_category_id')->references('id')->on('tech_support_categories')->onDelete('set null');
            $table->foreign('tech_support_problem_id')->references('id')->on('tech_support_problems')->onDelete('set null');
            
            // Agregar índices para mejor rendimiento con nombres cortos
            $table->index(['tech_support_category_id', 'created_at'], 'tsc_cat_created_idx');
            $table->index(['tech_support_problem_id', 'created_at'], 'tsc_prob_created_idx');
            $table->index(['problem_key', 'created_at'], 'tsc_key_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tech_support_conversations', function (Blueprint $table) {
            // Eliminar foreign keys
            $table->dropForeign(['tech_support_category_id']);
            $table->dropForeign(['tech_support_problem_id']);
            
            // Eliminar columnas
            $table->dropColumn([
                'tech_support_category_id',
                'tech_support_problem_id', 
                'problem_category_dynamic',
                'problem_key'
            ]);
        });
    }
};
