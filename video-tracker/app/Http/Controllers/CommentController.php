<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Game;
use App\Models\Videogame;
use App\Notifications\NuevoComentario;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, int $game)
    {
        $request->validate([
            'contenido' => 'required|min:3|max:500',
        ]);

        $comment = Comment::create([
            'user_id'   => Auth::id(),
            'game_id'   => $game,
            'contenido' => $request->contenido,
        ]);

        // Notificar a los usuarios que tienen ese juego en su biblioteca (excepto al comentador)
        $comment->load('user', 'game');
        Videogame::where('game_id', $game)
            ->where('user_id', '!=', Auth::id())
            ->with('user')
            ->get()
            ->each(fn($vj) => $vj->user->notify(new NuevoComentario($comment)));

        return back()->with('success', '¡Comentario publicado!');
    }
}
