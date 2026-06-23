<x-app-layout>
<div class="bg-slate-50 min-h-screen">

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Notificaciones</h1>
                @if($unreadCount = Auth::user()->unreadNotifications()->count())
                    <p class="text-sm text-slate-400 mt-0.5">{{ $unreadCount }} sin leer</p>
                @else
                    <p class="text-sm text-slate-400 mt-0.5">Todo al día</p>
                @endif
            </div>
            <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center">
                <x-heroicon-s-bell class="w-5 h-5 text-violet-500" />
            </div>
        </div>

        @if($notificaciones->isEmpty())
            <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-16 text-center">
                <x-heroicon-o-bell-slash class="w-12 h-12 text-slate-300 mx-auto mb-4" />
                <p class="text-slate-600 font-semibold">Sin notificaciones</p>
                <p class="text-slate-400 text-sm mt-1">Cuando alguien comente en un juego de tu biblioteca, aparecerá aquí</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($notificaciones as $notif)
                    @php $data = $notif->data; $leida = $notif->read_at !== null; @endphp
                    <div class="flex items-start gap-4 bg-white border rounded-2xl p-4 transition
                                {{ $leida ? 'border-slate-100' : 'border-violet-200 shadow-sm shadow-violet-100/50' }}">
                        <div class="w-9 h-9 shrink-0 rounded-xl {{ $leida ? 'bg-slate-100' : 'bg-violet-100' }} flex items-center justify-center">
                            <x-heroicon-s-chat-bubble-left-ellipsis class="w-4 h-4 {{ $leida ? 'text-slate-400' : 'text-violet-600' }}" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-slate-800 leading-snug">
                                <span class="font-semibold">{{ $data['usuario'] ?? 'Alguien' }}</span>
                                comentó en
                                <a href="{{ route('games.show', $data['game_id'] ?? '#') }}"
                                   class="font-semibold text-violet-700 hover:text-violet-600 transition">
                                    {{ $data['game_titulo'] ?? 'un juego' }}
                                </a>
                            </p>
                            @if(!empty($data['mensaje']))
                                <p class="text-xs text-slate-400 mt-1 truncate">"{{ $data['mensaje'] }}"</p>
                            @endif
                            <p class="text-[11px] text-slate-400 mt-1.5">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$leida)
                            <span class="w-2 h-2 bg-violet-500 rounded-full shrink-0 mt-1.5"></span>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notificaciones->links() }}
            </div>
        @endif
    </div>
</div>
</x-app-layout>
