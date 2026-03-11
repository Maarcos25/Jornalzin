<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('imagem')->nullable();
            $table->string('video')->nullable();
            $table->string('opcao1')->nullable();
            $table->string('opcao2')->nullable();
            $table->string('opcao3')->nullable();
            $table->string('opcao4')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'imagem',
                'video',
                'opcao1',
                'opcao2',
                'opcao3',
                'opcao4'
            ]);
        });
    }
};
