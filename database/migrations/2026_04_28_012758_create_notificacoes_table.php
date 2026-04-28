<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');        // quem recebe
            $table->unsignedBigInteger('ator_id');        // quem gerou
            $table->string('tipo');                       // like, comentario, seguidor, mensagem, post
            $table->string('mensagem');
            $table->string('link')->nullable();
            $table->boolean('lida')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};
