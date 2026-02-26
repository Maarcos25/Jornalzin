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
            $table->enum('tipo', ['imagem', 'video', 'texto', 'enquete']);
            $table->string('titulo');
            $table->text('texto')->nullable();
            $table->date('data');
            $table->unsignedBigInteger('visualizacoes')->default(0);

            $table->foreignId('id_usuario')
                ->constrained('users')
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
