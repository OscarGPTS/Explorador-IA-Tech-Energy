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
        Schema::create('news_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('description', 255)->nullable();
            $table->longText('content')->nullable();
            $table->string('image', 255)->nullable();
            $table->unsignedBigInteger('news_type_id'); // renombrado para consistencia
            $table->timestamps();

            $table->foreign('news_type_id')->references('id')->on('news_type')->onDelete('cascade');
        });

        Schema::create('user_news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('news_type_id'); // se vincula al tipo de noticia
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('news_type_id')->references('id')->on('news_type')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_news');
        Schema::dropIfExists('news');
        Schema::dropIfExists('news_type');
    }
};
