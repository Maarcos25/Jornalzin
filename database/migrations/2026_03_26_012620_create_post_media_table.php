<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_imagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')
                  ->constrained('posts')
                  ->cascadeOnDelete();
            $table->string('caminho');      // ex.: posts/imagens/abc123.jpg
            $table->unsignedSmallInteger('ordem')->default(0);
            $table->timestamps();
        });

        // Adiciona colunas extras na tabela posts (se ainda não existirem)
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'tamanho')) {
                $table->enum('tamanho', ['P', 'M', 'G', 'GG'])->default('M')->after('data');
            }
            if (!Schema::hasColumn('posts', 'opcao5')) {
                $table->string('opcao5')->nullable()->after('opcao4');
                $table->string('opcao6')->nullable()->after('opcao5');
                $table->string('opcao7')->nullable()->after('opcao6');
                $table->string('opcao8')->nullable()->after('opcao7');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_imagens');
    }
};
