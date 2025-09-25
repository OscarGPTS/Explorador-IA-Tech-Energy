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

        Schema::create('chatgroup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name', 255);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->text('message')->nullable(); // Cambiado de string(255) a text para soportar respuestas largas de IA
            $table->unsignedBigInteger('emisor_id')->nullable(); // Permitir null para mensajes de IA
            $table->unsignedBigInteger('receiver');
            $table->unsignedBigInteger('chatgroup_id')->nullable();
            $table->timestamps();

            $table->foreign('emisor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chatgroup_id')->references('id')->on('chatgroup')->onDelete('cascade');
        });

        Schema::create('file', function (Blueprint $table) {
            $table->id();
            $table->string('type', 40);
            $table->string('name', 255);
            $table->unsignedBigInteger('chat_id');
            $table->timestamps();

            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
        });

        Schema::create('user_chat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('chat_id');
            $table->unsignedBigInteger('chatgroup_id');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->foreign('chatgroup_id')->references('id')->on('chatgroup')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_chat');
        Schema::dropIfExists('file');
        Schema::dropIfExists('chats');
        Schema::dropIfExists('chatgroup');
    }
};
