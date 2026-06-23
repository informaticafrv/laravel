<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return view('search', ['q' => $q, 'juegos' => collect(), 'usuarios' => collect()]);
        }

        $juegos = Game::withAvg('videogames', 'puntuacion_personal')
            ->withCount('videogames')
            ->where('titulo', 'LIKE', "%{$q}%")
            ->take(8)
            ->get();

        $usuarios = User::where('name', 'LIKE', "%{$q}%")
            ->withCount('games')
            ->take(8)
            ->get();

        return view('search', compact('q', 'juegos', 'usuarios'));
    }
}
