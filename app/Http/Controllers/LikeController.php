<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function altera_like(Post $post)
    {
        $user = auth()->user();

        $like = Like::firstOrCreate([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        if (!$like->wasRecentlyCreated) {
            $like->delete();
            $liked = false;
        } else {
            $liked = true;
        }

        $total = $post->likes()->count();

        // Se for requisição AJAX retorna JSON, senão redireciona normalmente
        if (request()->expectsJson()) {
            return response()->json(['liked' => $liked, 'total' => $total]);
        }

        return back();
    }
}
