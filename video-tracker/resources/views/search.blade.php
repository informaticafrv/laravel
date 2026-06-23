<x-app-layout>
<div class="bg-slate-50 min-h-screen">

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Buscador --}}
        <form method="GET" action="{{ route('search') }}" class="mb-8">
            <div class="relative">
                <x-heroicon-o-magnifying-glass class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar juegos o usuarios..."
                       autofocus
                       class="w-full pl-12 pr-4 py-4 bg-white border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400
                              focus:outline-none focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 shadow-sm text-sm transition">
            </div>
        </form>

        @if(request('q'))
            @if($juegos->isEmpty() && $usuarios->isEmpty())
                <div class="text-center py-16">
                    <x-heroicon-o-face-frown class="w-12 h-12 text-slate-300 mx-auto mb-3" />
                    <p class="text-slate-600 font-semibold">Sin resultados para "{{ request('q') }}"</p>
                    <p class="text-slate-400 text-sm mt-1">Intenta con otro término</p>
                </div>
            @else
                {{-- Juegos --}}
                @if($juegos->isNotEmpty())
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-4">
                            <x-heroicon-s-squares-2x2 class="w-4 h-4 text-violet-500" />
                            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Juegos</h2>
                            <span class="text-xs text-slate-400">{{ $juegos->count() }} resultado{{ $juegos->count() !== 1 ? 's' : '' }}</span>
                        </div>
                        <div class="space-y-2">
                            @foreach($juegos as $game)
                                <a href="{{ route('games.show', $game->id) }}"
                                   class="flex items-center gap-4 bg-white border border-slate-100 rounded-2xl p-4 hover:border-violet-200 hover:shadow-sm transition group">
                                    <div class="w-12 h-12 shrink-0 rounded-xl overflow-hidden bg-slate-100">
                                        @if($game->portada_url)
                                            <img src="{{ $game->portada_url }}" class="w-full h-full object-cover">
                                        @elseif($game->portada)
                                            <img src="{{ asset('storage/' . $game->portada) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <x-heroicon-o-squares-2x2 class="w-6 h-6 text-slate-300" />
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-slate-900 group-hover:text-violet-700 transition truncate">{{ $game->titulo }}</p>
                                        <p class="text-sm text-slate-400">{{ $game->genero }}</p>
                                    </div>
                                    <x-heroicon-o-arrow-right class="w-4 h-4 text-slate-300 group-hover:text-violet-500 transition shrink-0" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Usuarios --}}
                @if($usuarios->isNotEmpty())
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <x-heroicon-s-users class="w-4 h-4 text-violet-500" />
                            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Usuarios</h2>
                            <span class="text-xs text-slate-400">{{ $usuarios->count() }} resultado{{ $usuarios->count() !== 1 ? 's' : '' }}</span>
                        </div>
                        <div class="space-y-2">
                            @foreach($usuarios as $user)
                                <a href="{{ route('users.show', $user->id) }}"
                                   class="flex items-center gap-4 bg-white border border-slate-100 rounded-2xl p-4 hover:border-violet-200 hover:shadow-sm transition group">
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-violet-100 flex items-center justify-center">
                                        <span class="text-lg font-bold text-violet-700">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-900 group-hover:text-violet-700 transition">{{ $user->name }}</p>
                                        <p class="text-sm text-slate-400">{{ $user->email }}</p>
                                    </div>
                                    <x-heroicon-o-arrow-right class="w-4 h-4 text-slate-300 group-hover:text-violet-500 transition shrink-0" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        @else
            <div class="text-center py-16">
                <x-heroicon-o-magnifying-glass class="w-12 h-12 text-slate-300 mx-auto mb-3" />
                <p class="text-slate-500 font-medium">Escribe para buscar juegos y usuarios</p>
                <p class="text-slate-400 text-sm mt-1">Mínimo 2 caracteres</p>
            </div>
        @endif
    </div>
</div>
</x-app-layout>
