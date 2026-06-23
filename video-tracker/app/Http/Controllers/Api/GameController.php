<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::withAvg('videogames', 'puntuacion_personal')
            ->withCount('videogames')
            ->paginate(15);

        return response()->json($games);
    }

    public function show(Game $game)
    {
        $game->load('achievements');
        $game->loadAvg('videogames', 'puntuacion_personal');
        $game->loadCount('videogames');

        return response()->json($game);
    }
}
