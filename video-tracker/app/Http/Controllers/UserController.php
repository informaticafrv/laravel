<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Videogame;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(User $user)
    {
        $user->load(['games', 'achievements']);

        $stats = [
            'total'      => Videogame::where('user_id', $user->id)->count(),
            'jugando'    => Videogame::where('user_id', $user->id)->where('estado', 'Jugando')->count(),
            'completados'=> Videogame::where('user_id', $user->id)->where('estado', 'Completado')->count(),
            'logros'     => $user->achievements->count(),
        ];

        $videojuegos = Videogame::with('game')
            ->where('user_id', $user->id)
            ->latest()
            ->take(6)
            ->get();

        $yaLeSigo    = Auth::check() && Auth::user()->following->contains($user->id);
        $seguidores  = $user->followers->count();
        $siguiendo   = $user->following->count();

        return view('users.show', compact('user', 'stats', 'videojuegos', 'yaLeSigo', 'seguidores', 'siguiendo'));
    }

    public function follow(User $user)
    {
        if (Auth::id() !== $user->id) {
            Auth::user()->following()->syncWithoutDetaching([$user->id]);
        }
        return back()->with('success', "Ahora sigues a {$user->name}");
    }

    public function unfollow(User $user)
    {
        Auth::user()->following()->detach($user->id);
        return back()->with('success', "Has dejado de seguir a {$user->name}");
    }
}
