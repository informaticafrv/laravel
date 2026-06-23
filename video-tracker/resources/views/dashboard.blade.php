<x-app-layout>
<div class="bg-slate-50 min-h-screen">

    {{-- Hero / Stats --}}
    <div class="bg-slate-900 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
                <div>
                    <p class="text-violet-400 text-xs font-semibold uppercase tracking-widest mb-1">Bienvenido de vuelta</p>
                    <h1 class="text-3xl font-bold text-white tracking-tight">{{ Auth::user()->name }}</h1>
                </div>
                <a href="{{ route('videogames.create') }}"
                   class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-lg shadow-violet-900/40">
                    <x-heroicon-o-plus class="w-4 h-4" /> Añadir juego
                </a>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4 mt-8">
                @php
                    $statItems = [
                        ['label' => 'En colección', 'value' => $stats['total'],       'icon' => 'bookmark',   'color' => 'text-violet-400', 'bg' => 'bg-violet-600/10'],
                        ['label' => 'Jugando',       'value' => $stats['jugando'],     'icon' => 'play',       'color' => 'text-emerald-400', 'bg' => 'bg-emerald-600/10'],
                        ['label' => 'Completados',   'value' => $stats['completados'], 'icon' => 'check-badge','color' => 'text-amber-400',   'bg' => 'bg-amber-600/10'],
                    ];
                @endphp
                @foreach($statItems as $stat)
                <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-5 flex items-center gap-4">
                    <div class="w-10 h-10 {{ $stat['bg'] }} rounded-xl flex items-center justify-center shrink-0">
                        <x-dynamic-component :component="'heroicon-s-' . $stat['icon']" class="w-5 h-5 {{ $stat['color'] }}" />
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">{{ $stat['value'] }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ $stat['label'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Contenido principal --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Añadidos recientemente</h2>
                <p class="text-sm text-slate-400">Los últimos juegos registrados por la comunidad</p>
            </div>
            <a href="{{ route('videogames.catalogo') }}"
               class="text-violet-600 hover:text-violet-700 text-sm font-semibold flex items-center gap-1 transition">
                Ver catálogo <x-heroicon-o-arrow-right class="w-4 h-4" />
            </a>
        </div>

        @if($ultimosJuegosGlobales->isEmpty())
            <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-16 text-center">
                <x-heroicon-o-squares-2x2 class="w-10 h-10 text-slate-300 mx-auto mb-4" />
                <p class="text-slate-500 font-medium">El catálogo está vacío</p>
                <a href="{{ route('videogames.create') }}" class="inline-flex items-center gap-2 mt-4 text-violet-600 font-semibold text-sm hover:text-violet-700 transition">
                    <x-heroicon-o-plus class="w-4 h-4" /> Añadir el primero
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($ultimosJuegosGlobales as $juego)
                    @php
                        $nota = $juego->videogames_avg_puntuacion_personal;
                        $color = $nota >= 8 ? 'text-emerald-600 bg-emerald-50' : ($nota >= 6 ? 'text-amber-600 bg-amber-50' : 'text-red-500 bg-red-50');
                    @endphp
                    <a href="{{ route('games.show', $juego->id) }}"
                       class="group bg-white border border-slate-100 rounded-2xl overflow-hidden hover:shadow-lg hover:shadow-slate-200/80 hover:border-violet-200 transition-all duration-200">
                        <div class="aspect-[3/4] relative overflow-hidden bg-slate-100">
                            @if($juego->portada_url)
                                <img src="{{ $juego->portada_url }}" alt="{{ $juego->titulo }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @elseif($juego->portada)
                                <img src="{{ asset('storage/' . $juego->portada) }}" alt="{{ $juego->titulo }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-violet-100 to-slate-100">
                                    <x-heroicon-o-squares-2x2 class="w-10 h-10 text-violet-300" />
                                </div>
                            @endif
                            @if($nota)
                                <span class="absolute top-2 right-2 text-xs font-bold px-2 py-0.5 rounded-full {{ $color }}">
                                    {{ number_format($nota, 1) }}
                                </span>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-semibold text-slate-900 truncate group-hover:text-violet-700 transition leading-tight">{{ $juego->titulo }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $juego->genero }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Accesos rápidos --}}
        <div class="mt-10">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Accesos rápidos</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @php
                    $quick = [
                        ['route' => 'videogames.index',  'label' => 'Biblioteca',  'desc' => 'Tu colección',        'icon' => 'bookmark',        'color' => 'text-violet-600', 'bg' => 'bg-violet-50 hover:bg-violet-100 border-violet-100'],
                        ['route' => 'rankings',          'label' => 'Rankings',    'desc' => 'Los mejores juegos',  'icon' => 'trophy',          'color' => 'text-amber-600',  'bg' => 'bg-amber-50 hover:bg-amber-100 border-amber-100'],
                        ['route' => 'search',            'label' => 'Buscar',      'desc' => 'Explora la comunidad','icon' => 'magnifying-glass', 'color' => 'text-sky-600',   'bg' => 'bg-sky-50 hover:bg-sky-100 border-sky-100'],
                        ['route' => 'videogames.export', 'label' => 'Exportar PDF','desc' => 'Descarga tu lista',   'icon' => 'arrow-down-tray', 'color' => 'text-slate-600', 'bg' => 'bg-slate-50 hover:bg-slate-100 border-slate-100'],
                    ];
                @endphp
                @foreach($quick as $q)
                <a href="{{ route($q['route']) }}"
                   class="group flex flex-col gap-3 p-5 rounded-2xl border {{ $q['bg'] }} transition">
                    <div class="w-9 h-9 bg-white rounded-xl shadow-sm flex items-center justify-center">
                        <x-dynamic-component :component="'heroicon-o-' . $q['icon']" class="w-5 h-5 {{ $q['color'] }}" />
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">{{ $q['label'] }}</p>
                        <p class="text-xs text-slate-400">{{ $q['desc'] }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
</x-app-layout>
