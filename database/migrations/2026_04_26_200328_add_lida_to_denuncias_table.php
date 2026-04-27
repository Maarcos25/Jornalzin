<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('denuncias', function (Blueprint $table) {
            $table->boolean('lida')->default(false)->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('denuncias', function (Blueprint $table) {
            $table->dropColumn('lida');
        });
    }
};