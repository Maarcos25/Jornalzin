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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('opcao5')->nullable()->after('opcao4');
            $table->string('opcao6')->nullable()->after('opcao5');
            $table->string('opcao7')->nullable()->after('opcao6');
            $table->string('opcao8')->nullable()->after('opcao7');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['opcao5', 'opcao6', 'opcao7', 'opcao8']);
        });
    }   
};
