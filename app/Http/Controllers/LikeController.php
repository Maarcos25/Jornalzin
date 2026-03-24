<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function altera_like(Post $post)
    {
        $user = auth()->user(); // Usuario logado

        $like = Like::firstOrCreate([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        // Apaga caso ja exista
        if (!$like->wasRecentlyCreated) {
            $like->delete();
        }

        return back();
    }
}
