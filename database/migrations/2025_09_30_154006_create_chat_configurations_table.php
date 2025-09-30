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
        Schema::create('chat_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_group_id')->constrained('chatgroup')->onDelete('cascade');
            $table->foreignId('user_agent_setting_id')->nullable()->constrained()->onDelete('set null');
            $table->json('context_data')->nullable(); // Datos de contexto específicos del chat
            $table->string('temperature')->default('0.7'); // Temperatura para la IA
            $table->integer('max_tokens')->default(2000);
            $table->boolean('use_recommendations')->default(true); // Si usar recomendaciones
            $table->boolean('use_news')->default(true); // Si usar noticias
            $table->boolean('is_active')->default(false); // Si esta configuración está activa para el chat
            $table->json('enabled_features')->nullable(); // Características habilitadas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_configurations');
    }
};
