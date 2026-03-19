<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Carbon;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Garante que existe pelo menos 1 usuário
        $usuarios = User::all();

        if ($usuarios->isEmpty()) {
            $usuarios = User::factory(5)->create();
        }

        $tipos = ['imagem', 'video', 'texto', 'enquete'];
        $tamanhos = ['P', 'M', 'G', 'GG'];

        foreach (range(1, 20) as $i) {
            Post::create([
                'tipo' => $tipos[array_rand($tipos)],
                'titulo' => 'Post Exemplo ' . $i,
                'texto' => 'Este é o conteúdo do post número ' . $i . '.',
                'data' => Carbon::now()->subDays(rand(0, 30)),
                'visualizacoes' => rand(0, 1000),
                'id_usuario' => $usuarios->random()->id,
                "tamanho" => $tamanhos[array_rand($tamanhos)]
            ]);
        }
    }
}
