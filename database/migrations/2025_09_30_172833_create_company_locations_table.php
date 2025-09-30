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
        Schema::create('company_locations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Código de ubicación (ej: HQ, BR01)
            $table->string('name'); // Nombre de la ubicación
            $table->enum('type', ['headquarters', 'branch', 'warehouse', 'datacenter', 'remote']);
            $table->text('address');
            $table->string('city');
            $table->string('state_province')->nullable();
            $table->string('country');
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->json('operating_days')->nullable(); // ['monday', 'tuesday', ...]
            $table->integer('capacity')->nullable(); // Número de empleados
            $table->integer('parking_spaces')->nullable();
            $table->json('facilities')->nullable(); // ['wifi', 'cafeteria', 'gym', ...]
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['type', 'is_active']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_locations');
    }
};
