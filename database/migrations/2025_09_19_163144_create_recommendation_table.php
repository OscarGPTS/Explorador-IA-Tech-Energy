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

        Schema::create('recommendations_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('description', 255)->nullable();
            $table->longText('content')->nullable();
            $table->string('image', 255)->nullable();
            $table->unsignedBigInteger('recommendation_type_id');
            $table->timestamps();

            $table->foreign('recommendation_type_id')->references('id')->on('recommendations_type')->onDelete('cascade');
        });
        
        Schema::create('user_recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('recommendation_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recommendation_id')->references('id')->on('recommendations')->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_recommendations');
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('recommendations_type');

    }
};
