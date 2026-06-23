<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'VideoTracker') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">

    @include('layouts.navigation')

    {{-- Toast de notificaciones flash --}}
    @if(session('success') || session('error'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed bottom-6 right-6 z-50"
        >
            @if(session('success'))
            <div class="flex items-center gap-3 bg-white border border-emerald-200 text-slate-800 px-5 py-4 rounded-2xl shadow-2xl shadow-slate-200 font-semibold text-sm max-w-sm">
                <span class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-600" />
                </span>
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-slate-300 hover:text-slate-600 transition">
                    <x-heroicon-o-x-mark class="w-4 h-4" />
                </button>
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-3 bg-white border border-red-200 text-slate-800 px-5 py-4 rounded-2xl shadow-2xl shadow-slate-200 font-semibold text-sm max-w-sm">
                <span class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center">
                    <x-heroicon-s-x-circle class="w-5 h-5 text-red-600" />
                </span>
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto text-slate-300 hover:text-slate-600 transition">
                    <x-heroicon-o-x-mark class="w-4 h-4" />
                </button>
            </div>
            @endif
        </div>
    @endif

    <main class="min-h-screen">
        @isset($header)
            <header class="bg-white border-b border-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                    {{ $header }}
                </div>
            </header>
        @endisset
        {{ $slot }}
    </main>

</body>
</html>
