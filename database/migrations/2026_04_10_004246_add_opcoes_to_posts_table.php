<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $cols = ['opcao5','opcao6','opcao7','opcao8'];
            foreach ($cols as $col) {
                if (!Schema::hasColumn('posts', $col)) {
                    $table->string($col)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $cols = ['opcao5','opcao6','opcao7','opcao8'];
            $existing = array_filter($cols, fn($c) => Schema::hasColumn('posts', $c));
            if ($existing) $table->dropColumn(array_values($existing));
        });
    }
};