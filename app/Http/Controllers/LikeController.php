<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Http\Controllers\NotificacaoController;
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
            // ── Notificação de like ──
            NotificacaoController::criar(
                $post->id_usuario,
                $user->id,
                'like',
                $user->nome . ' curtiu seu post "' . $post->titulo . '"',
                route('posts.show', $post->id)
            );
        }

        $total = $post->likes()->count();

        if (request()->expectsJson()) {
            return response()->json(['liked' => $liked, 'total' => $total]);
        }

        return back();
    }
}
