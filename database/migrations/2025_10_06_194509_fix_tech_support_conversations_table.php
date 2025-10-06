<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tech_support_conversations', function (Blueprint $table) {
            // Verificar si las columnas no existen antes de agregarlas
            if (!Schema::hasColumn('tech_support_conversations', 'tech_support_category_id')) {
                $table->foreignId('tech_support_category_id')->nullable()->after('employee_id');
            }
            
            if (!Schema::hasColumn('tech_support_conversations', 'tech_support_problem_id')) {
                $table->foreignId('tech_support_problem_id')->nullable()->after('tech_support_category_id');
            }
            
            if (!Schema::hasColumn('tech_support_conversations', 'problem_category_dynamic')) {
                $table->string('problem_category_dynamic')->nullable()->after('tech_support_problem_id');
            }
            
            if (!Schema::hasColumn('tech_support_conversations', 'problem_key')) {
                $table->string('problem_key')->nullable()->after('problem_category_dynamic');
            }
        });

        // Agregar foreign keys en una segunda operación
        Schema::table('tech_support_conversations', function (Blueprint $table) {
            if (!$this->foreignKeyExists('tech_support_conversations', 'tech_support_conversations_tech_support_category_id_foreign')) {
                $table->foreign('tech_support_category_id')->references('id')->on('tech_support_categories')->onDelete('set null');
            }
            
            if (!$this->foreignKeyExists('tech_support_conversations', 'tech_support_conversations_tech_support_problem_id_foreign')) {
                $table->foreign('tech_support_problem_id')->references('id')->on('tech_support_problems')->onDelete('set null');
            }
        });

        // Agregar índices en una tercera operación
        Schema::table('tech_support_conversations', function (Blueprint $table) {
            if (!$this->indexExists('tech_support_conversations', 'tsc_cat_created_idx')) {
                $table->index(['tech_support_category_id', 'created_at'], 'tsc_cat_created_idx');
            }
            
            if (!$this->indexExists('tech_support_conversations', 'tsc_prob_created_idx')) {
                $table->index(['tech_support_problem_id', 'created_at'], 'tsc_prob_created_idx');
            }
            
            if (!$this->indexExists('tech_support_conversations', 'tsc_key_created_idx')) {
                $table->index(['problem_key', 'created_at'], 'tsc_key_created_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tech_support_conversations', function (Blueprint $table) {
            // Eliminar índices
            $table->dropIndex('tsc_cat_created_idx');
            $table->dropIndex('tsc_prob_created_idx');
            $table->dropIndex('tsc_key_created_idx');
            
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

    /**
     * Check if foreign key exists
     */
    private function foreignKeyExists($table, $name): bool
    {
        $keys = collect(DB::select("SHOW CREATE TABLE {$table}"))->first();
        return str_contains($keys->{'Create Table'}, "CONSTRAINT `{$name}`");
    }

    /**
     * Check if index exists
     */
    private function indexExists($table, $name): bool
    {
        $indexes = collect(DB::select("SHOW INDEX FROM {$table}"));
        return $indexes->contains('Key_name', $name);
    }
};
