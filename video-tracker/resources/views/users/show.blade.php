<x-app-layout>
<div class="bg-slate-50 min-h-screen">

    {{-- Hero --}}
    <div class="bg-slate-900 border-b border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-6">
                <div class="w-20 h-20 rounded-2xl bg-violet-600 flex items-center justify-center shrink-0 shadow-xl shadow-violet-900/40">
                    <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-violet-400 text-xs font-semibold uppercase tracking-widest mb-1">Perfil público</p>
                    <h1 class="text-3xl font-bold text-white tracking-tight">{{ $user->name }}</h1>
                    <p class="text-slate-400 text-sm mt-1">Miembro desde {{ $user->created_at->format('M Y') }}</p>
                </div>
                @auth
                    @if(Auth::id() !== $user->id)
                        <form method="POST"
                              action="{{ $yaLeSigo ? route('users.unfollow', $user->id) : route('users.follow', $user->id) }}">
                            @csrf
                            @if($yaLeSigo) @method('DELETE') @endif
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition
                                           {{ $yaLeSigo
                                               ? 'bg-slate-700 text-slate-300 hover:bg-red-900/40 hover:text-red-400 border border-slate-600 hover:border-red-700/40'
                                               : 'bg-violet-600 hover:bg-violet-500 text-white shadow-lg shadow-violet-900/40' }}">
                                @if($yaLeSigo)
                                    <x-heroicon-s-user-minus class="w-4 h-4" /> Dejar de seguir
                                @else
                                    <x-heroicon-s-user-plus class="w-4 h-4" /> Seguir
                                @endif
                            </button>
                        </form>
                    @endif
                @endauth
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-4 gap-4 mt-8">
                @php
                    $userStats = [
                        ['label' => 'Juegos',       'value' => $stats['total']],
                        ['label' => 'Completados',  'value' => $stats['completados']],
                        ['label' => 'Seguidores',   'value' => $seguidores],
                        ['label' => 'Siguiendo',    'value' => $siguiendo],
                    ];
                @endphp
                @foreach($userStats as $s)
                    <div class="bg-slate-800/60 border border-slate-700/60 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-white">{{ $s['value'] }}</p>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $s['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($juegosRecientes->isEmpty())
            <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-16 text-center">
                <x-heroicon-o-bookmark class="w-10 h-10 text-slate-300 mx-auto mb-3" />
                <p class="text-slate-500 font-medium">{{ $user->name }} no tiene juegos registrados</p>
            </div>
        @else
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-900">Juegos recientes</h2>
                <span class="text-xs text-slate-400">Últimos 8</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($juegosRecientes as $game)
                    @php
                        $nota = $game->pivot->puntuacion_personal;
                        $notaColor = $nota >= 8 ? 'bg-emerald-500' : ($nota >= 6 ? 'bg-amber-400' : 'bg-red-400');
                        $estadoBadge = [
                            'Completado' => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                            'Jugando'    => 'text-violet-700 bg-violet-50 border-violet-200',
                            'Pendiente'  => 'text-slate-600 bg-slate-100 border-slate-200',
                            'Abandonado' => 'text-red-600 bg-red-50 border-red-200',
                        ][$game->pivot->estado] ?? 'text-slate-600 bg-slate-100 border-slate-200';
                    @endphp
                    <a href="{{ route('games.show', $game->id) }}"
                       class="group bg-white border border-slate-100 rounded-2xl overflow-hidden hover:shadow-md hover:border-violet-200 transition-all">
                        <div class="aspect-[3/4] relative overflow-hidden bg-slate-100">
                            @if($game->portada_url)
                                <img src="{{ $game->portada_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @elseif($game->portada)
                                <img src="{{ asset('storage/' . $game->portada) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-violet-100 to-slate-100">
                                    <x-heroicon-o-squares-2x2 class="w-8 h-8 text-violet-300" />
                                </div>
                            @endif
                            <span class="absolute top-2 left-2 text-[10px] font-bold text-white px-1.5 py-0.5 rounded {{ $notaColor }}">
                                {{ number_format($nota, 1) }}
                            </span>
                        </div>
                        <div class="p-2.5">
                            <p class="text-xs font-semibold text-slate-900 truncate">{{ $game->titulo }}</p>
                            <span class="inline-block mt-1 text-[10px] font-semibold border rounded px-1.5 py-px {{ $estadoBadge }}">
                                {{ $game->pivot->estado }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
</x-app-layout>
