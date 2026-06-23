<?php

namespace App\Http\Controllers;

use App\Models\Videogame;
use App\Models\Game;
use App\Models\Achievement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class VideogameController extends Controller
{
    public function dashboard()
    {
        $user_id = Auth::id();
        $stats = [
            'total'      => Videogame::where('user_id', $user_id)->count(),
            'jugando'    => Videogame::where('user_id', $user_id)->where('estado', 'Jugando')->count(),
            'completados'=> Videogame::where('user_id', $user_id)->where('estado', 'Completado')->count(),
        ];
        $ultimosJuegosGlobales = Game::withAvg('videogames', 'puntuacion_personal')
            ->latest()
            ->take(5)
            ->get();
        return view('dashboard', compact('ultimosJuegosGlobales', 'stats'));
    }

    public function index()
    {
        //carga la biblioteca del usuario
        $videojuegos = Videogame::with('game')->where('user_id', Auth::id())->get();
        return view('biblioteca', compact('videojuegos'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        //validaciones
        $validated = $request->validate([
            'titulo' => 'required|string',
            'genero' => 'required|string',
            'plataforma' => 'required',
            'puntuacion_personal' => 'required|numeric|min:0|max:10',
            'estado' => 'required',
            'portada' => 'nullable|image|max:2048',
            'portada_url' => 'nullable|string',
            'igdb_id' => 'nullable|integer',
        ]);

        $igdbId = $request->igdb_id ?? 0;

        $game = Game::firstOrCreate(
            ['titulo' => $validated['titulo']],
            [
                'genero' => $validated['genero'],
                'portada_url' => $request->portada_url,
                'igdb_id' => $igdbId
            ]
        );

        if ($request->hasFile('portada')) {
            $path = $request->file('portada')->store('portadas', 'public');
            $game->update(['portada' => $path]);
        }

        if ($game->wasRecentlyCreated) {
            $tipos = ['Bronce', 'Plata', 'Oro', 'Platino'];
            for ($i = 1; $i <= 20; $i++) {
                $tipo = $tipos[($i - 1) % 4];
                $nombre = match(true) {
                    $i === 1  => "Bienvenido a " . $game->titulo,
                    $i === 10 => "Mitad del Camino",
                    $i === 20 => "Leyenda de " . $game->titulo,
                    $i % 5 === 0 => "Desafío Especial Nivel " . ($i / 5),
                    default => "Logro de " . $tipo . " #" . $i
                };
                Achievement::create([
                    'game_id'     => $game->id,
                    'nombre'      => $nombre,
                    'descripcion' => "Has desbloqueado el desafío número {$i} en la categoría {$tipo}.",
                    'imagen_url'  => 'https://cdn-icons-png.flaticon.com/512/3112/3112946.png',
                ]);
            }
        }

        Videogame::updateOrCreate(
            ['user_id' => Auth::id(), 'game_id' => $game->id],
            [
                'plataforma' => $validated['plataforma'],
                'puntuacion_personal' => $validated['puntuacion_personal'],
                'estado' => $validated['estado'],
            ]
        );

        return redirect()->route('videogames.index')->with('success', '¡Juego añadido!');
    }

    public function catalogo(Request $request)
    {
        //muestra los registros de la base de datos, también busca y filtra
        $buscar = $request->input('search');
        $genero = $request->input('genero');

        $juegosGlobales = Game::withAvg('videogames', 'puntuacion_personal')
            ->when($buscar, function ($query) use ($buscar) {
                return $query->where('titulo', 'LIKE', "%{$buscar}%");
            })
            ->when($genero, function ($query) use ($genero) {
                return $query->where('genero', $genero);
            })
            ->paginate(6)
            ->withQueryString();

        return view('catalogo', compact('juegosGlobales'));
    }

    public function votar(Request $request, $game_id)
    {
        $validated = $request->validate([
            'puntuacion_personal' => 'required|numeric|min:0|max:10',
            'estado' => 'required',
            'plataforma' => 'required|in:PC,PS5,PS4,Xbox,Switch',
        ]);

        Videogame::updateOrCreate(
            ['user_id' => Auth::id(), 'game_id' => $game_id],
            [
                'puntuacion_personal' => $validated['puntuacion_personal'],
                'estado' => $validated['estado'],
                'plataforma' => $validated['plataforma'],
            ]
        );

        return back()->with('success', '¡Voto registrado en tu biblioteca!');
    }

    public function edit($id)
    {
        $videojuego = Videogame::with('game')->where('user_id', Auth::id())->findOrFail($id);
        return view('edit', compact('videojuego'));
    }

    public function update(Request $request, $id)
    {
        $videojuego = Videogame::where('user_id', Auth::id())->findOrFail($id);
        $validated = $request->validate([
            'puntuacion_personal'  => 'required|numeric|min:0|max:10',
            'estado'               => 'required',
            'plataforma'           => 'required',
            'nota_grafica'         => 'nullable|numeric|min:0|max:10',
            'nota_historia'        => 'nullable|numeric|min:0|max:10',
            'nota_jugabilidad'     => 'nullable|numeric|min:0|max:10',
            'nota_duracion'        => 'nullable|numeric|min:0|max:10',
        ]);
        $videojuego->update($validated);
        return redirect()->route('videogames.index')->with('success', '¡Juego actualizado!');
    }

    public function exportPdf()
    {
        $user        = Auth::user();
        $videojuegos = Videogame::with('game')->where('user_id', $user->id)->get();
        $stats = [
            'total'       => $videojuegos->count(),
            'completados' => $videojuegos->where('estado', 'Completado')->count(),
            'jugando'     => $videojuegos->where('estado', 'Jugando')->count(),
            'nota_media'  => $videojuegos->avg('puntuacion_personal') ?? 0,
        ];

        $pdf = Pdf::loadView('pdf.biblioteca', compact('user', 'videojuegos', 'stats'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("biblioteca-{$user->name}.pdf");
    }

    public function destroy($id)
    {
        $videojuego = Videogame::where('user_id', Auth::id())->findOrFail($id);
        $videojuego->delete();
        return back()->with('success', 'Juego eliminado de tu biblioteca');
    }
    //vista detalle
    public function show($id)
    {
        $juego = Game::with(['comments.user', 'achievements'])->findOrFail($id);
        $user  = Auth::user()->load('achievements', 'games');

        $misLogrosIds        = $user->achievements->pluck('id')->toArray();
        $loTengoEnBiblioteca = $user->games->contains('id', (int) $id);

        $vidId = null;
        if ($loTengoEnBiblioteca) {
            $vidId = Videogame::where('user_id', $user->id)->where('game_id', $id)->value('id');
        }

        return view('show', compact('juego', 'misLogrosIds', 'loTengoEnBiblioteca', 'vidId'));
    }

    private function getIgdbToken()
    {
        $response = Http::post('https://id.twitch.tv/oauth2/token', [
            'client_id' => config('services.igdb.client_id'),
            'client_secret' => config('services.igdb.client_secret'),
            'grant_type' => 'client_credentials',
        ]);

        return $response->json()['access_token'] ?? null;
    }

    public function toggleAchievement($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            $user->achievements()->toggle($id);
        }

        return back();
    }

    public function searchIgdb(Request $request)
    {
        $search = $request->query('q');
        $token = $this->getIgdbToken();

        if (!$token || strlen($search) < 3) return response()->json([]);

        $response = Http::withHeaders([
            'Client-ID' => config('services.igdb.client_id'),
            'Authorization' => 'Bearer ' . $token,
        ])->withBody("search \"{$search}\"; fields name, cover.url, genres.name, id; limit 5;", 'text/plain')
        ->post('https://api.igdb.com/v4/games');

        $games = collect($response->json())->map(function($game) {
            return [
                'name' => $game['name'],
                'id' => $game['id'],
                'cover' => isset($game['cover']['url']) 
                    ? str_replace('t_thumb', 't_cover_big', "https:" . $game['cover']['url']) 
                    : null,
                'genre' => $game['genres'][0]['name'] ?? 'Otros'
            ];
        });

        return response()->json($games);
    }
}