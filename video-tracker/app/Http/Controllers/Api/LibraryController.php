<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Videogame;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $library = Videogame::with('game')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($library);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id'            => 'required|exists:games,id',
            'plataforma'         => 'required|in:PC,PS5,PS4,Xbox,Switch',
            'puntuacion_personal'=> 'required|numeric|min:0|max:10',
            'estado'             => 'required|in:Pendiente,Jugando,Completado,Abandonado',
        ]);

        $entry = Videogame::updateOrCreate(
            ['user_id' => $request->user()->id, 'game_id' => $validated['game_id']],
            $validated
        );

        return response()->json($entry->load('game'), 201);
    }

    public function destroy(Request $request, Videogame $videogame)
    {
        if ($videogame->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $videogame->delete();
        return response()->json(['message' => 'Eliminado de tu biblioteca']);
    }
}
