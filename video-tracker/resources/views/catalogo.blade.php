<x-app-layout>
<div class="bg-slate-50 min-h-screen">

    {{-- Header + filtros --}}
    <div class="bg-white border-b border-slate-100 sticky top-16 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form method="GET" action="{{ route('videogames.catalogo') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar en el catálogo..."
                           oninput="clearTimeout(this._t); this._t = setTimeout(() => this.form.submit(), 400)"
                           class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:bg-white transition">
                </div>
                <select name="genero" onchange="this.form.submit()"
                        class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-slate-50 text-slate-700 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500">
                    <option value="">Todos los géneros</option>
                    @foreach(['Acción','Aventura','RPG','Estrategia','Deportes','Simulación','Puzzle','Terror','Plataformas','Otros'] as $g)
                        <option value="{{ $g }}" {{ request('genero') == $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
                @if(request('search') || request('genero'))
                    <a href="{{ route('videogames.catalogo') }}"
                       class="inline-flex items-center gap-2 border border-slate-200 text-slate-500 hover:text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium transition">
                        <x-heroicon-o-x-mark class="w-4 h-4" /> Limpiar
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Cabecera --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Catálogo</h1>
                <p class="text-sm text-slate-400 mt-0.5">{{ $juegosGlobales->total() }} juegos disponibles</p>
            </div>
            <a href="{{ route('videogames.create') }}"
               class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-sm shadow-violet-200">
                <x-heroicon-o-plus class="w-4 h-4" /> Añadir
            </a>
        </div>

        @if($juegosGlobales->isEmpty())
            <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-20 text-center">
                <x-heroicon-o-magnifying-glass class="w-10 h-10 text-slate-300 mx-auto mb-4" />
                <p class="text-slate-600 font-semibold">Sin resultados</p>
                <p class="text-slate-400 text-sm mt-1">Prueba con otros términos de búsqueda</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($juegosGlobales as $game)
                    @php
                        $nota = $game->videogames_avg_puntuacion_personal;
                        $notaColor = $nota >= 8 ? 'bg-emerald-500' : ($nota >= 6 ? 'bg-amber-400' : 'bg-red-400');
                    @endphp
                    <div x-data="{ open: false }" class="relative group">
                        {{-- Tarjeta juego --}}
                        <a href="{{ route('games.show', $game->id) }}"
                           class="block bg-white border border-slate-100 rounded-2xl overflow-hidden hover:shadow-lg hover:border-violet-200 transition-all duration-200">
                            <div class="aspect-[3/4] relative overflow-hidden bg-slate-100">
                                @if($game->portada_url)
                                    <img src="{{ $game->portada_url }}" alt="{{ $game->titulo }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @elseif($game->portada)
                                    <img src="{{ asset('storage/' . $game->portada) }}" alt="{{ $game->titulo }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-violet-100 to-slate-100">
                                        <x-heroicon-o-squares-2x2 class="w-8 h-8 text-violet-300" />
                                    </div>
                                @endif
                                @if($nota)
                                    <span class="absolute top-2 left-2 text-[11px] font-bold text-white px-1.5 py-0.5 rounded-md {{ $notaColor }}">
                                        {{ number_format($nota, 1) }}
                                    </span>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                                <button @click.prevent="open = true"
                                        class="absolute bottom-2 inset-x-2 bg-violet-600 text-white text-xs font-semibold py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 translate-y-1 group-hover:translate-y-0">
                                    + Añadir a biblioteca
                                </button>
                            </div>
                            <div class="p-2.5">
                                <p class="text-xs font-semibold text-slate-900 truncate leading-tight">{{ $game->titulo }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $game->genero }}</p>
                            </div>
                        </a>

                        {{-- Modal votar --}}
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             @click.away="open = false"
                             @keydown.escape.window="open = false"
                             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
                            <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="font-bold text-slate-900 text-sm">{{ $game->titulo }}</h3>
                                        <p class="text-xs text-slate-400">Añadir a mi biblioteca</p>
                                    </div>
                                    <button @click="open = false" class="p-1 text-slate-400 hover:text-slate-600 transition">
                                        <x-heroicon-o-x-mark class="w-5 h-5" />
                                    </button>
                                </div>
                                <form action="{{ route('videogames.votar', $game->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Plataforma</label>
                                        <select name="plataforma" required class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-violet-500">
                                            @foreach(['PC','PS5','PS4','Xbox','Switch'] as $p)
                                                <option>{{ $p }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</label>
                                        <select name="estado" required class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-violet-500">
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Jugando">Jugando</option>
                                            <option value="Completado">Completado</option>
                                            <option value="Abandonado">Abandonado</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Puntuación (0–10)</label>
                                        <input type="number" name="puntuacion_personal" step="0.1" min="0" max="10" required
                                               class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm text-center font-bold text-violet-600 focus:outline-none focus:border-violet-500">
                                    </div>
                                    <button type="submit"
                                            class="w-full bg-violet-600 hover:bg-violet-500 text-white font-semibold py-2.5 rounded-xl text-sm transition mt-1">
                                        Guardar en biblioteca
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="mt-8">
                {{ $juegosGlobales->links() }}
            </div>
        @endif
    </div>
</div>
</x-app-layout>
