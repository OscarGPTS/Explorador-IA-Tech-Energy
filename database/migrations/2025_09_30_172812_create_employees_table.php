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
        // Crear tabla temporal para almacenar información de empleados
        // Esta tabla puede contener empleados que aún no han accedido al sistema
        Schema::create('temp_employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->nullable(); // ID de empleado (ej: EMP001)
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name')->virtualAs('concat(first_name, " ", last_name)');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('extension')->nullable();
            $table->string('position'); // Cargo/Puesto
            $table->string('department');
            $table->string('location')->nullable(); // Ubicación física
            $table->string('manager_email')->nullable();
            $table->date('hire_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Relación con tabla users (solo si el empleado tiene acceso via Google Auth)
            $table->unsignedBigInteger('user_id')->nullable(); // Relación con tabla users si existe
            
            // Metadatos para manejo temporal
            $table->timestamp('data_imported_at')->nullable(); // Cuándo se importó la data
            $table->string('import_source')->nullable(); // Fuente de importación (CSV, API, etc.)
            $table->timestamp('last_sync_at')->nullable(); // Última sincronización
            
            $table->timestamps();
            
            // Indexes for better search performance
            $table->index(['department', 'is_active']);
            $table->index(['status', 'is_active']);
            $table->index('employee_id');
            $table->index('user_id');
            $table->fullText(['first_name', 'last_name', 'position', 'department']);
            
            // Foreign key constraint (opcional, si existe tabla users)
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_employees');
    }
};
