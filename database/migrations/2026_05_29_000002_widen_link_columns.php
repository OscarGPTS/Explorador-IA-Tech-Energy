<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Las URLs de feeds (p. ej. Google News RSS) superan los 255 caracteres,
     * así que ampliamos las columnas de enlaces a TEXT.
     */
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->text('external_link')->nullable()->change();
            $table->text('image_url')->nullable()->change();
        });

        Schema::table('recommendations', function (Blueprint $table) {
            $table->text('external_link')->nullable()->change();
            $table->text('image_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('external_link')->nullable()->change();
            $table->string('image_url')->nullable()->change();
        });

        Schema::table('recommendations', function (Blueprint $table) {
            $table->string('external_link')->nullable()->change();
            $table->string('image_url')->nullable()->change();
        });
    }
};
