<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nome');
            $table->string('sobrenome');
            $table->string('ra')->unique();
            $table->string('telefone')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->enum('tipo', ['administrador', 'editor', 'leitor'])->default('leitor');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nome',
                'sobrenome',
                'ra',
                'telefone',
                'data_nascimento',
                'tipo'
            ]);
        });
    }
};
