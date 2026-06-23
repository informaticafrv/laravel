<x-app-layout>
<div class="bg-slate-50 min-h-screen">

    {{-- Portada hero --}}
    <div class="relative bg-slate-900 overflow-hidden">
        @if($juego->portada_url || $juego->portada)
            <div class="absolute inset-0 opacity-20 blur-xl scale-110">
                <img src="{{ $juego->portada_url ?? asset('storage/' . $juego->portada) }}"
                     class="w-full h-full object-cover">
            </div>
        @endif
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col sm:flex-row gap-8 items-start">

                {{-- Portada --}}
                <div class="w-40 sm:w-52 shrink-0">
                    <div class="aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl shadow-black/60 bg-slate-800">
                        @if($juego->portada_url)
                            <img src="{{ $juego->portada_url }}" alt="{{ $juego->titulo }}" class="w-full h-full object-cover">
                        @elseif($juego->portada)
                            <img src="{{ asset('storage/' . $juego->portada) }}" alt="{{ $juego->titulo }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <x-heroicon-o-squares-2x2 class="w-14 h-14 text-slate-600" />
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Info --}}
                <div class="flex-1 py-2">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-violet-300 bg-violet-900/40 border border-violet-700/40 rounded-full px-3 py-1 mb-3">
                        <x-heroicon-s-tag class="w-3.5 h-3.5" /> {{ $juego->genero }}
                    </span>
                    <h1 class="text-4xl font-bold text-white tracking-tight leading-tight">{{ $juego->titulo }}</h1>

                    {{-- Nota global --}}
                    @php $notaGlobal = $juego->notaMedia(); @endphp
                    @if($notaGlobal)
                        <div class="flex items-center gap-2 mt-4">
                            <x-heroicon-s-star class="w-5 h-5 text-amber-400" />
                            <span class="text-2xl font-bold text-white">{{ number_format($notaGlobal, 1) }}</span>
                            <span class="text-slate-400 text-sm">/ 10 · nota media global</span>
                        </div>
                    @endif

                    {{-- Barra de logros --}}
                    @php
                        $totalLogros = $juego->achievements->count();
                        $conseguidos = $juego->achievements->whereIn('id', $misLogrosIds)->count();
                        $porcentaje  = $totalLogros > 0 ? round(($conseguidos / $totalLogros) * 100) : 0;
                    @endphp

                    <div class="mt-6 max-w-sm">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Logros</span>
                            <span class="text-xs font-bold text-violet-300">{{ $conseguidos }}/{{ $totalLogros }} · <?php echo $porcentaje; ?>%</span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-2">
                            <div class="bg-gradient-to-r from-violet-500 to-violet-400 h-2 rounded-full transition-all duration-700"
                                 style="width: <?php echo $porcentaje; ?>%"></div>
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex items-center flex-wrap gap-3 mt-8">
                        <a href="{{ route('videogames.catalogo') }}"
                           class="inline-flex items-center gap-2 border border-slate-700 text-slate-300 hover:text-white hover:border-slate-600 px-4 py-2 rounded-xl text-sm font-semibold transition">
                            <x-heroicon-o-arrow-left class="w-4 h-4" /> Catálogo
                        </a>
                        @if($loTengoEnBiblioteca)
                            <span class="inline-flex items-center gap-2 bg-emerald-600/20 border border-emerald-600/30 text-emerald-400 px-4 py-2 rounded-xl text-sm font-semibold">
                                <x-heroicon-s-check-circle class="w-4 h-4" /> En tu biblioteca
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Columna principal --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Logros --}}
            <div x-data="{ open: false }">
                <button @click="open = !open"
                        class="w-full flex items-center justify-between bg-white border border-slate-100 rounded-2xl px-6 py-4 hover:border-violet-200 transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                            <x-heroicon-s-trophy class="w-5 h-5 text-amber-500" />
                        </div>
                        <div class="text-left">
                            <p class="font-semibold text-slate-900 text-sm">Logros del juego</p>
                            <p class="text-xs text-slate-400">{{ $conseguidos }} de {{ $totalLogros }} desbloqueados</p>
                        </div>
                    </div>
                    <span :class="open ? 'rotate-180' : ''" class="transition-transform duration-200 inline-flex">
                        <x-heroicon-o-chevron-down class="w-5 h-5 text-slate-400" />
                    </span>
                </button>

                <div x-show="open" x-transition class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @forelse($juego->achievements as $index => $logro)
                        @php
                            $esMio = in_array($logro->id, $misLogrosIds);
                            $tier = match(true) {
                                $index + 1 <= 8  => ['Bronce',  'from-orange-400 to-orange-600',        'text-orange-700 bg-orange-50 border-orange-200'],
                                $index + 1 <= 14 => ['Plata',   'from-slate-300 to-slate-500',           'text-slate-700 bg-slate-100 border-slate-300'],
                                $index + 1 <= 19 => ['Oro',     'from-yellow-400 to-yellow-600',         'text-yellow-700 bg-yellow-50 border-yellow-200'],
                                default          => ['Platino', 'from-violet-500 to-violet-700',         'text-violet-700 bg-violet-50 border-violet-200'],
                            };
                        @endphp
                        <div class="flex items-center gap-3 p-4 bg-white border rounded-xl transition
                                    {{ $esMio ? 'border-violet-200 shadow-sm shadow-violet-100' : 'border-slate-100 opacity-60' }}">
                            <div class="w-12 h-12 shrink-0 rounded-xl p-0.5 bg-gradient-to-br {{ $tier[1] }}">
                                <div class="w-full h-full rounded-[10px] overflow-hidden bg-white flex items-center justify-center">
                                    <img src="{{ $logro->imagen_url }}" class="w-8 h-8 object-contain {{ $esMio ? '' : 'grayscale' }}">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <span class="text-[10px] font-bold border rounded px-1.5 py-px {{ $tier[2] }}">{{ $tier[0] }}</span>
                                </div>
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $logro->nombre }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ Str::limit($logro->descripcion, 40) }}</p>
                            </div>
                            @if($loTengoEnBiblioteca)
                                <form action="{{ route('achievements.toggle', $logro->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center transition shrink-0
                                                   {{ $esMio ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-400 hover:bg-violet-100 hover:text-violet-600' }}">
                                        @if($esMio)
                                            <x-heroicon-s-check class="w-4 h-4" />
                                        @else
                                            <x-heroicon-o-plus class="w-4 h-4" />
                                        @endif
                                    </button>
                                </form>
                            @else
                                <x-heroicon-o-lock-closed class="w-4 h-4 text-slate-300 shrink-0" />
                            @endif
                        </div>
                    @empty
                        <p class="col-span-full text-center text-slate-400 py-8 text-sm">Sin logros disponibles.</p>
                    @endforelse
                </div>
            </div>

            {{-- Comentarios --}}
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-4">Comentarios de la comunidad</h2>

                @auth
                <form action="{{ route('comments.store', $juego->id) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="bg-white border border-slate-100 rounded-2xl p-4">
                        <textarea name="contenido" rows="3"
                                  class="w-full bg-transparent border-0 resize-none focus:ring-0 text-sm text-slate-700 placeholder-slate-400 p-0"
                                  placeholder="Comparte tu opinión sobre {{ $juego->titulo }}..."></textarea>
                        <div class="flex justify-end pt-3 border-t border-slate-50">
                            <button type="submit"
                                    class="bg-violet-600 hover:bg-violet-500 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                                Publicar comentario
                            </button>
                        </div>
                    </div>
                </form>
                @endauth

                <div class="space-y-3">
                    @forelse($juego->comments as $comment)
                        <div class="flex gap-4 bg-white border border-slate-100 rounded-2xl p-5">
                            <div class="w-9 h-9 rounded-xl bg-violet-100 flex items-center justify-center shrink-0">
                                <span class="text-sm font-bold text-violet-700">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-sm font-semibold text-slate-800">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $comment->contenido }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-10 text-center">
                            <x-heroicon-o-chat-bubble-left-ellipsis class="w-8 h-8 text-slate-300 mx-auto mb-2" />
                            <p class="text-slate-400 text-sm">Sé el primero en comentar</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            {{-- Info del juego --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-5">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Información</h3>
                <dl class="space-y-3">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-500 flex items-center gap-2">
                            <x-heroicon-o-tag class="w-4 h-4" /> Género
                        </dt>
                        <dd class="text-sm font-semibold text-slate-800">{{ $juego->genero }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-500 flex items-center gap-2">
                            <x-heroicon-o-users class="w-4 h-4" /> En bibliotecas
                        </dt>
                        <dd class="text-sm font-semibold text-slate-800">{{ $juego->videogames()->count() }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-500 flex items-center gap-2">
                            <x-heroicon-o-trophy class="w-4 h-4" /> Total logros
                        </dt>
                        <dd class="text-sm font-semibold text-slate-800">{{ $totalLogros }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Tu progreso --}}
            @if($loTengoEnBiblioteca)
                @php $miRegistro = Auth::user()->games->firstWhere('id', $juego->id); @endphp
                @if($miRegistro)
                <div class="bg-violet-50 border border-violet-100 rounded-2xl p-5">
                    <h3 class="text-xs font-semibold text-violet-500 uppercase tracking-wider mb-4">Tu registro</h3>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Estado</span>
                            <span class="text-sm font-semibold text-slate-800">{{ $miRegistro->pivot->estado }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Plataforma</span>
                            <span class="text-sm font-semibold text-slate-800">{{ $miRegistro->pivot->plataforma }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Tu nota</span>
                            <span class="text-xl font-bold text-violet-600">{{ number_format($miRegistro->pivot->puntuacion_personal, 1) }}</span>
                        </div>
                    </div>
                    @if($vidId)
                    <a href="{{ route('videogames.edit', $vidId) }}"
                       class="mt-4 w-full flex items-center justify-center gap-2 border border-violet-200 text-violet-700 hover:bg-violet-100 py-2 rounded-xl text-sm font-semibold transition">
                        <x-heroicon-o-pencil class="w-4 h-4" /> Editar registro
                    </a>
                    @endif
                </div>
                @endif
            @endif
        </div>

    </div>
</div>
</x-app-layout>
