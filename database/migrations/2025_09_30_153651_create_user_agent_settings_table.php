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
        Schema::create('user_agent_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('agent_role_id')->constrained()->onDelete('cascade');
            $table->text('custom_prompt')->nullable(); // Prompt personalizado del usuario
            $table->text('custom_instructions')->nullable(); // Instrucciones personalizadas
            $table->json('preferences')->nullable(); // Preferencias adicionales (JSON)
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
            
            // Un usuario solo puede tener una configuración activa a la vez
            $table->unique(['user_id', 'is_active'], 'user_active_setting');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_agent_settings');
    }
};
