<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('denuncias', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');                          // 'post' ou 'comentario'
            $table->unsignedBigInteger('referencia_id');     // id do post ou comentário
            $table->string('motivo');
            $table->text('descricao')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('denuncias');
    }
};