<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center h-16 gap-6">

            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0 group">
                <div class="w-8 h-8 bg-violet-600 rounded-lg flex items-center justify-center shadow-lg group-hover:bg-violet-500 transition">
                    <x-heroicon-s-play class="w-4 h-4 text-white" />
                </div>
                <span class="text-white font-bold text-lg tracking-tight">Video<span class="text-violet-400">Tracker</span></span>
            </a>

            {{-- Separador --}}
            <div class="hidden sm:block h-6 w-px bg-slate-700"></div>

            {{-- Nav links desktop --}}
            <div class="hidden sm:flex items-center gap-1">
                @php
                    $navLinks = [
                        ['route' => 'dashboard',          'label' => 'Inicio',    'icon' => 'home'],
                        ['route' => 'videogames.index',   'label' => 'Biblioteca','icon' => 'bookmark'],
                        ['route' => 'videogames.catalogo','label' => 'Catálogo',  'icon' => 'squares-2x2'],
                        ['route' => 'rankings',           'label' => 'Rankings',  'icon' => 'trophy'],
                    ];
                @endphp
                @foreach($navLinks as $link)
                    @php $isActive = request()->routeIs($link['route']); @endphp
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition
                              {{ $isActive ? 'bg-violet-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <x-dynamic-component :component="'heroicon-o-' . $link['icon']" class="w-4 h-4" />
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Buscador (desktop, centrado) --}}
            <div class="hidden sm:flex flex-1 max-w-sm mx-auto">
                <form action="{{ route('search') }}" method="GET" class="relative w-full">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="Buscar juegos y usuarios..."
                           class="w-full pl-9 pr-4 py-2 bg-slate-800 border border-slate-700 text-slate-200 placeholder-slate-500
                                  rounded-xl text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">
                </form>
            </div>

            {{-- Acciones derecha --}}
            <div class="hidden sm:flex items-center gap-2 ml-auto">
                @php $unread = Auth::user()->unreadNotifications->count(); @endphp
                <a href="{{ route('notifications.index') }}"
                   class="relative p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                    <x-heroicon-o-bell class="w-5 h-5" />
                    @if($unread > 0)
                        <span class="absolute top-1 right-1 w-2 h-2 bg-violet-500 rounded-full ring-2 ring-slate-900"></span>
                    @endif
                </a>

                <x-dropdown align="right" width="52">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2.5 pl-3 pr-2 py-1.5 rounded-xl border border-slate-700 text-slate-300 hover:text-white hover:border-slate-600 hover:bg-slate-800 transition text-sm font-medium">
                            <div class="w-6 h-6 rounded-lg bg-violet-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                            <x-heroicon-m-chevron-down class="w-3.5 h-3.5 text-slate-500" />
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Cuenta</p>
                            <p class="text-sm font-semibold text-slate-900 truncate mt-0.5">{{ Auth::user()->name }}</p>
                        </div>
                        <div class="py-1">
                            <x-dropdown-link :href="route('users.show', Auth::id())" class="flex items-center gap-2.5">
                                <x-heroicon-o-user class="w-4 h-4 text-slate-400" /> Mi Perfil Público
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('videogames.export')" class="flex items-center gap-2.5">
                                <x-heroicon-o-arrow-down-tray class="w-4 h-4 text-slate-400" /> Exportar a PDF
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2.5">
                                <x-heroicon-o-cog-6-tooth class="w-4 h-4 text-slate-400" /> Ajustes de Perfil
                            </x-dropdown-link>
                        </div>
                        <div class="border-t border-slate-100 py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="flex items-center gap-2.5 text-red-600"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" /> Cerrar Sesión
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Hamburguesa (móvil) --}}
            <button @click="open = !open" class="sm:hidden ml-auto p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                <x-heroicon-o-bars-3 x-show="!open" class="w-5 h-5" />
                <x-heroicon-o-x-mark x-show="open"  class="w-5 h-5" />
            </button>
        </div>
    </div>

    {{-- Menú móvil --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="sm:hidden bg-slate-900 border-t border-slate-800 pb-4">
        <div class="px-4 pt-3 pb-2">
            <form action="{{ route('search') }}" method="GET">
                <div class="relative">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar..."
                           class="w-full pl-9 pr-4 py-2.5 bg-slate-800 border border-slate-700 text-slate-200 placeholder-slate-500 rounded-xl text-sm focus:outline-none focus:border-violet-500">
                </div>
            </form>
        </div>
        <div class="px-3 space-y-0.5">
            @foreach($navLinks as $link)
                @php $isActive = request()->routeIs($link['route']); @endphp
                <a href="{{ route($link['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ $isActive ? 'bg-violet-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <x-dynamic-component :component="'heroicon-o-' . $link['icon']" class="w-4 h-4" />
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition">
                <x-heroicon-o-bell class="w-4 h-4" />
                Notificaciones
                @if($unread > 0)<span class="ml-auto w-2 h-2 bg-violet-500 rounded-full"></span>@endif
            </a>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-800 px-3 space-y-0.5">
            <a href="{{ route('users.show', Auth::id()) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition">
                <x-heroicon-o-user class="w-4 h-4" /> Mi Perfil Público
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition">
                <x-heroicon-o-cog-6-tooth class="w-4 h-4" /> Ajustes
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:text-red-300 hover:bg-slate-800 transition">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" /> Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</nav>
