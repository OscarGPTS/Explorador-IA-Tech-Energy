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
        Schema::create('tech_support_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index(); // ID de sesión del chat
            $table->string('user_ip')->nullable(); // IP del usuario
            $table->string('user_agent')->nullable(); // Navegador del usuario
            $table->string('employee_id')->nullable(); // ID del empleado si está logueado
            $table->enum('problem_category', [
                'computer', 'internet', 'email', 'printer', 
                'software', 'access', 'google', 'office', 'other'
            ])->nullable();
            $table->string('problem_type')->nullable(); // Tipo específico del problema
            $table->text('user_message'); // Mensaje del usuario
            $table->text('bot_response'); // Respuesta del bot
            $table->enum('response_type', [
                'solution_provided', 'escalated_to_it', 'partial_solution', 'information_request'
            ])->default('solution_provided');
            $table->boolean('problem_solved')->default(false); // Si el usuario indicó que se solucionó
            $table->boolean('escalated_to_human')->default(false); // Si se escaló a IT
            $table->string('resolution_method')->nullable(); // Cómo se resolvió
            $table->integer('interaction_step')->default(1); // Paso en la conversación
            $table->json('context_data')->nullable(); // Contexto adicional
            $table->timestamp('resolved_at')->nullable(); // Cuándo se resolvió
            $table->timestamps();
            
            $table->index(['problem_category', 'created_at']);
            $table->index(['session_id', 'interaction_step']);
            $table->index(['escalated_to_human', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_support_conversations');
    }
};
