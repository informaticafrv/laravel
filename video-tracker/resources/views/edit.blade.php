<x-app-layout>
<div class="bg-slate-50 min-h-screen">

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('videogames.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition mb-4">
                <x-heroicon-o-arrow-left class="w-4 h-4" /> Volver a la biblioteca
            </a>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Editar registro</h1>
            <p class="text-slate-400 text-sm mt-0.5">{{ $videojuego->game->titulo }}</p>
        </div>

        <form action="{{ route('videogames.update', $videojuego->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            {{-- Estado y plataforma --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-6 space-y-4">
                <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Datos del juego</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Plataforma</label>
                        <select name="plataforma" class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-slate-50 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">
                            @foreach(['PC','PS5','PS4','Xbox','Switch'] as $p)
                                <option value="{{ $p }}" {{ $videojuego->plataforma == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Estado</label>
                        <select name="estado" class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-slate-50 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">
                            @foreach(['Pendiente' => 'Pendiente', 'Jugando' => 'Jugando ahora', 'Completado' => 'Completado', 'Abandonado' => 'Abandonado'] as $val => $label)
                                <option value="{{ $val }}" {{ $videojuego->estado == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Nota global --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-6">
                <label class="block text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                    <x-heroicon-s-star class="w-4 h-4 text-amber-400" /> Puntuación global
                </label>
                <div class="flex items-center justify-center gap-4">
                    <input type="range" name="puntuacion_personal" min="0" max="10" step="0.1"
                           value="{{ $videojuego->puntuacion_personal }}"
                           x-data x-ref="slider" x-model="$el.value"
                           @input="$refs.display.textContent = parseFloat($el.value).toFixed(1)"
                           class="flex-1 accent-violet-600 cursor-pointer">
                    <span x-data="{ v: {{ $videojuego->puntuacion_personal }} }"
                          x-ref="display"
                          class="w-16 h-16 bg-violet-50 border border-violet-100 rounded-2xl flex items-center justify-center text-2xl font-bold text-violet-600 shrink-0">
                        {{ number_format($videojuego->puntuacion_personal, 1) }}
                    </span>
                </div>
                <div class="flex justify-between text-xs text-slate-400 mt-2 px-1">
                    <span>0</span><span>5</span><span>10</span>
                </div>
            </div>

            {{-- Notas por categoría --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-6">
                <h2 class="text-sm font-semibold text-slate-700 mb-1">Puntuaciones por categoría</h2>
                <p class="text-xs text-slate-400 mb-4">Opcionales · de 0 a 10</p>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        'nota_grafica'     => ['Gráficos',    'squares-2x2'],
                        'nota_historia'    => ['Historia',    'book-open'],
                        'nota_jugabilidad' => ['Jugabilidad', 'cursor-arrow-rays'],
                        'nota_duracion'    => ['Duración',    'clock'],
                    ] as $campo => [$label, $icon])
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5 flex items-center gap-1.5">
                                <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-3.5 h-3.5 text-slate-400" />
                                {{ $label }}
                            </label>
                            <input type="number" name="{{ $campo }}" step="0.1" min="0" max="10"
                                   value="{{ $videojuego->$campo }}" placeholder="—"
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-center font-bold text-slate-700 bg-slate-50 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('videogames.index') }}"
                   class="px-4 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-700 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-500 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm shadow-violet-200">
                    <x-heroicon-o-check class="w-4 h-4" /> Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
