<x-app-layout>
<div class="bg-slate-50 min-h-screen">

    {{-- Header --}}
    <div class="bg-slate-900 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-500/20 rounded-xl flex items-center justify-center">
                    <x-heroicon-s-trophy class="w-6 h-6 text-amber-400" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Rankings</h1>
                    <p class="text-slate-400 text-sm">Los mejores de la comunidad</p>
                </div>
            </div>
            @if($miPosicion)
                <div class="mt-6 inline-flex items-center gap-2 bg-violet-600/20 border border-violet-600/30 text-violet-300 px-4 py-2.5 rounded-xl text-sm font-semibold">
                    <x-heroicon-s-star class="w-4 h-4" />
                    Tu posición entre los más completistas: #{{ $miPosicion }}
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- TOP: Mejor valorados --}}
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-50 flex items-center gap-3">
                    <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                        <x-heroicon-s-star class="w-4 h-4 text-amber-500" />
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900 text-sm">Mejor valorados</p>
                        <p class="text-xs text-slate-400">Por nota media</p>
                    </div>
                </div>
                <ol class="divide-y divide-slate-50">
                    @forelse($mejoresJuegos as $i => $game)
                        @php
                            $medal = match($i) { 0 => ['text-amber-500','bg-amber-50'], 1 => ['text-slate-400','bg-slate-100'], 2 => ['text-orange-600','bg-orange-50'], default => ['text-slate-500','bg-slate-50'] };
                        @endphp
                        <li class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50/60 transition">
                            <span class="w-7 h-7 {{ $medal[1] }} rounded-lg flex items-center justify-center text-xs font-bold {{ $medal[0] }} shrink-0">
                                {{ $i + 1 }}
                            </span>
                            <div class="w-8 h-8 shrink-0 rounded-lg overflow-hidden bg-slate-100">
                                @if($game->portada_url)
                                    <img src="{{ $game->portada_url }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <x-heroicon-o-squares-2x2 class="w-4 h-4 text-slate-300" />
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('games.show', $game->id) }}"
                                   class="text-sm font-semibold text-slate-900 hover:text-violet-700 transition truncate block">
                                    {{ $game->titulo }}
                                </a>
                                <p class="text-xs text-slate-400">{{ $game->genero }}</p>
                            </div>
                            <span class="text-sm font-bold text-amber-600 shrink-0">
                                {{ number_format($game->videogames_avg_puntuacion_personal, 1) }}
                            </span>
                        </li>
                    @empty
                        <li class="px-5 py-8 text-center text-slate-400 text-sm">Sin datos</li>
                    @endforelse
                </ol>
            </div>

            {{-- TOP: Más populares --}}
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-50 flex items-center gap-3">
                    <div class="w-8 h-8 bg-violet-50 rounded-lg flex items-center justify-center">
                        <x-heroicon-s-fire class="w-4 h-4 text-violet-500" />
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900 text-sm">Más populares</p>
                        <p class="text-xs text-slate-400">Por número de registros</p>
                    </div>
                </div>
                <ol class="divide-y divide-slate-50">
                    @forelse($juegosPopulares as $i => $game)
                        @php
                            $medal = match($i) { 0 => ['text-amber-500','bg-amber-50'], 1 => ['text-slate-400','bg-slate-100'], 2 => ['text-orange-600','bg-orange-50'], default => ['text-slate-500','bg-slate-50'] };
                        @endphp
                        <li class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50/60 transition">
                            <span class="w-7 h-7 {{ $medal[1] }} rounded-lg flex items-center justify-center text-xs font-bold {{ $medal[0] }} shrink-0">
                                {{ $i + 1 }}
                            </span>
                            <div class="w-8 h-8 shrink-0 rounded-lg overflow-hidden bg-slate-100">
                                @if($game->portada_url)
                                    <img src="{{ $game->portada_url }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <x-heroicon-o-squares-2x2 class="w-4 h-4 text-slate-300" />
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('games.show', $game->id) }}"
                                   class="text-sm font-semibold text-slate-900 hover:text-violet-700 transition truncate block">
                                    {{ $game->titulo }}
                                </a>
                                <p class="text-xs text-slate-400">{{ $game->genero }}</p>
                            </div>
                            <span class="text-xs font-semibold text-violet-600 bg-violet-50 px-2 py-0.5 rounded-full shrink-0">
                                {{ $game->videogames_count }}
                            </span>
                        </li>
                    @empty
                        <li class="px-5 py-8 text-center text-slate-400 text-sm">Sin datos</li>
                    @endforelse
                </ol>
            </div>

            {{-- TOP: Completistas --}}
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-50 flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <x-heroicon-s-check-badge class="w-4 h-4 text-emerald-500" />
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900 text-sm">Completistas</p>
                        <p class="text-xs text-slate-400">Más juegos completados</p>
                    </div>
                </div>
                <ol class="divide-y divide-slate-50">
                    @forelse($usuariosActivos as $i => $user)
                        @php
                            $medal = match($i) { 0 => ['text-amber-500','bg-amber-50'], 1 => ['text-slate-400','bg-slate-100'], 2 => ['text-orange-600','bg-orange-50'], default => ['text-slate-500','bg-slate-50'] };
                        @endphp
                        <li class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50/60 transition">
                            <span class="w-7 h-7 {{ $medal[1] }} rounded-lg flex items-center justify-center text-xs font-bold {{ $medal[0] }} shrink-0">
                                {{ $i + 1 }}
                            </span>
                            <div class="w-8 h-8 shrink-0 rounded-xl bg-violet-100 flex items-center justify-center">
                                <span class="text-sm font-bold text-violet-700">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('users.show', $user->id) }}"
                                   class="text-sm font-semibold text-slate-900 hover:text-violet-700 transition truncate block">
                                    {{ $user->name }}
                                </a>
                                <p class="text-xs text-slate-400">{{ $user->games_count }} en colección</p>
                            </div>
                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full shrink-0">
                                {{ $user->completados_count ?? 0 }}
                            </span>
                        </li>
                    @empty
                        <li class="px-5 py-8 text-center text-slate-400 text-sm">Sin datos</li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
