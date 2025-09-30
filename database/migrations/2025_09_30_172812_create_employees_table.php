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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique(); // ID de empleado (ej: EMP001)
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
            $table->timestamps();
            
            // Indexes for better search performance
            $table->index(['department', 'is_active']);
            $table->index(['status', 'is_active']);
            $table->index('employee_id');
            $table->fullText(['first_name', 'last_name', 'position', 'department']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
