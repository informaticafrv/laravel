<x-app-layout>
<div class="bg-slate-50 min-h-screen">

    {{-- Header --}}
    <div class="bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Mi Biblioteca</h1>
                    <p class="text-sm text-slate-400 mt-0.5">{{ $videojuegos->count() }} {{ Str::plural('juego', $videojuegos->count()) }} en tu colección</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('videogames.export') }}"
                       class="inline-flex items-center gap-2 border border-slate-200 text-slate-600 hover:text-slate-900 hover:border-slate-300 px-3.5 py-2 rounded-xl text-sm font-semibold transition">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" /> PDF
                    </a>
                    <a href="{{ route('videogames.create') }}"
                       class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-sm shadow-violet-200">
                        <x-heroicon-o-plus class="w-4 h-4" /> Añadir juego
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Modal de confirmación de borrado --}}
        <div x-data="deleteModal()"
             @open-delete.window="open($event.detail)"
             x-show="show"
             x-cloak
             class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6"
                 @click.away="show = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center shrink-0">
                        <x-heroicon-s-trash class="w-5 h-5 text-red-500" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 text-base">Eliminar juego</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            ¿Seguro que quieres eliminar <span class="font-semibold text-slate-700" x-text="gameTitle"></span> de tu biblioteca? Esta acción no se puede deshacer.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 justify-end">
                    <button @click="show = false"
                            class="px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition">
                        Cancelar
                    </button>
                    <form :action="formAction" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-sm">
                            <x-heroicon-o-trash class="w-4 h-4" /> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if($videojuegos->isEmpty())
            <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-20 text-center">
                <x-heroicon-o-bookmark class="w-12 h-12 text-slate-300 mx-auto mb-4" />
                <p class="text-slate-600 font-semibold text-lg">Tu biblioteca está vacía</p>
                <p class="text-slate-400 text-sm mt-1">Empieza añadiendo tu primer juego</p>
                <a href="{{ route('videogames.create') }}"
                   class="inline-flex items-center gap-2 mt-6 bg-violet-600 hover:bg-violet-500 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
                    <x-heroicon-o-plus class="w-4 h-4" /> Añadir juego
                </a>
            </div>
        @else
            {{-- Tabla --}}
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/80">
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Juego</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider hidden sm:table-cell">Plataforma</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider hidden md:table-cell">Estado</th>
                            <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Nota</th>
                            <th class="text-right px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($videojuegos as $videojuego)
                            @php
                                $estadoConfig = [
                                    'Completado' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200',  'icon' => 'check-badge'],
                                    'Jugando'    => ['bg' => 'bg-violet-50 text-violet-700 border-violet-200',     'icon' => 'play-circle'],
                                    'Pendiente'  => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200',       'icon' => 'clock'],
                                    'Abandonado' => ['bg' => 'bg-red-50 text-red-600 border-red-200',             'icon' => 'x-circle'],
                                ];
                                $est = $estadoConfig[$videojuego->estado] ?? $estadoConfig['Pendiente'];
                                $nota = $videojuego->puntuacion_personal;
                                $notaColor = $nota >= 8 ? 'text-emerald-600' : ($nota >= 6 ? 'text-amber-500' : 'text-red-500');
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 shrink-0 rounded-xl overflow-hidden bg-slate-100">
                                            @if($videojuego->game->portada_url)
                                                <img src="{{ $videojuego->game->portada_url }}" alt="" class="w-full h-full object-cover">
                                            @elseif($videojuego->game->portada)
                                                <img src="{{ asset('storage/' . $videojuego->game->portada) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <x-heroicon-o-squares-2x2 class="w-5 h-5 text-slate-300" />
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('games.show', $videojuego->game->id) }}"
                                               class="font-semibold text-slate-900 hover:text-violet-700 transition text-sm leading-tight">
                                                {{ $videojuego->game->titulo }}
                                            </a>
                                            <p class="text-xs text-slate-400 mt-0.5">{{ $videojuego->game->genero }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 hidden sm:table-cell">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-slate-100 rounded-lg px-2.5 py-1">
                                        <x-heroicon-o-computer-desktop class="w-3.5 h-3.5" />
                                        {{ $videojuego->plataforma }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 hidden md:table-cell">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold border rounded-full px-2.5 py-1 {{ $est['bg'] }}">
                                        <x-dynamic-component :component="'heroicon-s-' . $est['icon']" class="w-3.5 h-3.5" />
                                        {{ $videojuego->estado }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-2xl font-bold {{ $notaColor }}">{{ number_format($nota, 1) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('videogames.edit', $videojuego->id) }}"
                                           class="p-2 rounded-lg text-slate-400 hover:text-violet-600 hover:bg-violet-50 transition">
                                            <x-heroicon-o-pencil class="w-4 h-4" />
                                        </a>
                                        <button type="button"
                                                @click="$dispatch('open-delete', { action: '{{ route('videogames.destroy', $videojuego->id) }}', title: '{{ addslashes($videojuego->game->titulo) }}' })"
                                                class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition">
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Resumen de estadísticas --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
                @php
                    $resumen = [
                        ['label' => 'Completados', 'value' => $videojuegos->where('estado','Completado')->count(), 'color' => 'text-emerald-600'],
                        ['label' => 'Jugando',     'value' => $videojuegos->where('estado','Jugando')->count(),    'color' => 'text-violet-600'],
                        ['label' => 'Pendientes',  'value' => $videojuegos->where('estado','Pendiente')->count(),  'color' => 'text-slate-600'],
                        ['label' => 'Nota media',  'value' => number_format($videojuegos->avg('puntuacion_personal') ?? 0, 1), 'color' => 'text-amber-600'],
                    ];
                @endphp
                @foreach($resumen as $r)
                <div class="bg-white border border-slate-100 rounded-2xl p-4 text-center">
                    <p class="text-2xl font-bold {{ $r['color'] }}">{{ $r['value'] }}</p>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $r['label'] }}</p>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
function deleteModal() {
    return {
        show: false,
        formAction: '',
        gameTitle: '',
        open(detail) {
            this.formAction = detail.action;
            this.gameTitle  = detail.title;
            this.show = true;
        }
    }
}
</script>
</x-app-layout>
