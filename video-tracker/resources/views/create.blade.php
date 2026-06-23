<x-app-layout>
<div class="bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('videogames.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition mb-4">
                <x-heroicon-o-arrow-left class="w-4 h-4" /> Volver a la biblioteca
            </a>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Añadir juego</h1>
            <p class="text-slate-400 text-sm mt-0.5">Busca en IGDB o introduce los datos manualmente</p>
        </div>

        {{-- Errores --}}
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
                <x-heroicon-s-exclamation-circle class="w-5 h-5 text-red-500 shrink-0 mt-0.5" />
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('videogames.store') }}" method="POST" enctype="multipart/form-data"
              x-data="{
                search: '',
                results: [],
                selectedGame: null,
                async fetchGames() {
                    if(this.search.length < 3) { this.results = []; return; }
                    const r = await fetch('/api/search-igdb?q=' + encodeURIComponent(this.search));
                    this.results = await r.json();
                },
                selectGame(game) {
                    this.selectedGame = game;
                    this.search = game.name;
                    this.results = [];
                    document.getElementById('real_titulo').value = game.name;
                    document.getElementById('genero_select').value = game.genre;
                    document.getElementById('portada_url_hidden').value = game.cover ?? '';
                    document.getElementById('igdb_id_hidden').value = game.id;
                }
              }"
              class="space-y-5">
            @csrf

            {{-- Búsqueda IGDB --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-6">
                <h2 class="text-sm font-semibold text-slate-700 mb-3">Buscar juego</h2>
                <div class="relative">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                    <input type="text" x-model="search" @input.debounce.500ms="fetchGames()"
                           placeholder="Escribe el nombre del juego..."
                           autocomplete="off"
                           class="w-full pl-9 pr-4 py-3 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:bg-white transition">

                    {{-- Desplegable resultados --}}
                    <div x-show="results.length > 0"
                         class="absolute z-50 w-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden">
                        <template x-for="game in results" :key="game.id">
                            <div @click="selectGame(game)"
                                 class="flex items-center gap-3 px-4 py-3 hover:bg-violet-50 cursor-pointer transition border-b border-slate-50 last:border-0">
                                <img :src="game.cover" class="w-8 h-11 object-cover rounded-lg bg-slate-200" x-show="game.cover">
                                <div x-show="!game.cover" class="w-8 h-11 bg-slate-100 rounded-lg flex items-center justify-center">
                                    <x-heroicon-o-squares-2x2 class="w-4 h-4 text-slate-300" />
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900" x-text="game.name"></p>
                                    <p class="text-xs text-violet-600 font-medium" x-text="game.genre"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Game seleccionado --}}
                <template x-if="selectedGame">
                    <div class="mt-3 flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <x-heroicon-s-check-circle class="w-4 h-4 text-emerald-600 shrink-0" />
                        <span class="text-sm font-semibold text-emerald-800" x-text="'Seleccionado: ' + selectedGame.name"></span>
                    </div>
                </template>

                <input type="hidden" name="titulo"      id="real_titulo"        :value="search" required>
                <input type="hidden" name="portada_url" id="portada_url_hidden">
                <input type="hidden" name="igdb_id"     id="igdb_id_hidden">
            </div>

            {{-- Género y plataforma --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-6 space-y-4">
                <h2 class="text-sm font-semibold text-slate-700">Detalles</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Género</label>
                        <select name="genero" id="genero_select" required
                                class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-slate-50 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">
                            <option value="" disabled selected>Selecciona</option>
                            @foreach(['Acción','Aventura','RPG','Shooter','Plataformas','Deportes','Estrategia','Terror','Lucha','Simulación','Puzzle','Indie','Otros'] as $g)
                                <option value="{{ $g }}">{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Plataforma</label>
                        <select name="plataforma" class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-slate-50 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">
                            @foreach(['PC','PS5','PS4','Xbox','Switch','Retro'] as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Estado</label>
                        <select name="estado" class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-slate-50 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">
                            <option value="Pendiente">Pendiente</option>
                            <option value="Jugando">Jugando</option>
                            <option value="Completado">Completado</option>
                            <option value="Abandonado">Abandonado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Puntuación (0–10)</label>
                        <input type="number" name="puntuacion_personal" step="0.1" min="0" max="10" placeholder="8.5" required
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-center font-bold text-violet-600 bg-slate-50 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">
                    </div>
                </div>
            </div>

            {{-- Portada manual --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-6">
                <h2 class="text-sm font-semibold text-slate-700 mb-1">Carátula propia</h2>
                <p class="text-xs text-slate-400 mb-4">Opcional · Solo si no la has buscado por IGDB</p>
                <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl p-8 cursor-pointer hover:border-violet-300 hover:bg-violet-50/50 transition">
                    <x-heroicon-o-photo class="w-8 h-8 text-slate-300 mb-2" />
                    <span class="text-sm text-slate-400 font-medium">Haz clic para subir imagen</span>
                    <span class="text-xs text-slate-300 mt-1">PNG, JPG · máx 2MB</span>
                    <input type="file" name="portada" accept="image/*" class="hidden">
                </label>
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('videogames.index') }}"
                   class="px-4 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-700 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-500 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm shadow-violet-200">
                    <x-heroicon-o-plus class="w-4 h-4" /> Añadir a la biblioteca
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
