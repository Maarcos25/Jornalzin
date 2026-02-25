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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo', ['texto', 'imagem', 'video']);
        $table->string('titulo');
        $table->text('conteudo')->nullable();
        $table->string('arquivo')->nullable();
        $table->date('data_postagem');

        $table->unsignedBigInteger('visualizacoes')->default(0);

        $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
