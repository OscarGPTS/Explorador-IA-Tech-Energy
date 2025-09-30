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
        Schema::create('agent_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del rol (ej: "Agente de RH")
            $table->string('slug')->unique(); // Slug único (ej: "hr_agent")
            $table->text('description'); // Descripción del rol
            $table->text('system_prompt'); // Prompt del sistema para este rol
            $table->text('instructions'); // Instrucciones específicas
            $table->json('capabilities')->nullable(); // Capacidades específicas (JSON)
            $table->string('icon')->nullable(); // Icono del rol
            $table->string('color')->default('#3B82F6'); // Color asociado
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_roles');
    }
};
