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
        Schema::create('company_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('document_code')->unique()->nullable(); // Código del documento
            $table->enum('type', ['policy', 'procedure', 'manual', 'form', 'template', 'guide', 'other']);
            $table->enum('category', ['hr', 'it', 'finance', 'operations', 'legal', 'marketing', 'general']);
            $table->text('description')->nullable();
            $table->string('file_path')->nullable(); // Ruta del archivo físico
            $table->string('external_url')->nullable(); // URL externa (SharePoint, Google Drive, etc.)
            $table->string('file_type')->nullable(); // pdf, docx, xlsx, etc.
            $table->integer('file_size')->nullable(); // en bytes
            $table->string('version', 10)->default('1.0');
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('owner_email'); // Responsable del documento
            $table->string('department');
            $table->enum('access_level', ['public', 'internal', 'confidential'])->default('internal');
            $table->json('tags')->nullable(); // ['onboarding', 'security', ...]
            $table->text('summary')->nullable(); // Resumen del contenido
            $table->integer('download_count')->default(0);
            $table->timestamp('last_reviewed')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['type', 'category', 'is_active']);
            $table->index(['department', 'access_level']);
            $table->index('document_code');
            $table->fullText(['title', 'description', 'summary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_documents');
    }
};
