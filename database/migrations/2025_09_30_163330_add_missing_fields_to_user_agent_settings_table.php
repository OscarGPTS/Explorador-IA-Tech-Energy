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
        Schema::table('user_agent_settings', function (Blueprint $table) {
            // Verificar si las columnas ya existen antes de agregarlas
            if (!Schema::hasColumn('user_agent_settings', 'name')) {
                $table->string('name')->after('agent_role_id')->default('Mi Configuración');
            }
            
            if (!Schema::hasColumn('user_agent_settings', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('preferences');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_agent_settings', function (Blueprint $table) {
            $table->dropColumn(['name', 'is_default']);
        });
    }
};
