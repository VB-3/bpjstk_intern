<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Portal Magang BPJS Ketenagakerjaan') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen"
             class="fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm lg:hidden"
             @click="sidebarOpen = false"
             x-transition.opacity>
        </div>

        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">

            <!-- Top Bar -->
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200/70 shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center gap-4">

                    <!-- Mobile Hamburger -->
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden text-gray-500 hover:text-green-700 p-1.5 rounded-lg hover:bg-green-50 transition-colors focus:outline-none"
                            aria-label="Toggle menu">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Mobile brand -->
                    <span class="lg:hidden font-extrabold text-green-700 text-sm tracking-wide">BPJSTK Portal</span>

                    <!-- Page Header (desktop) -->
                    @isset($header)
                        <div class="hidden lg:flex flex-1 items-center">
                            {{ $header }}
                        </div>
                    @endisset

                    <!-- Right spacer on mobile -->
                    <div class="flex-1 lg:hidden"></div>

                    <!-- Date chip (desktop) -->
                    <div class="hidden lg:flex items-center gap-2 text-xs font-semibold text-gray-500 bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-xl ml-auto">
                        <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>

                <!-- Mobile page header below top bar -->
                @isset($header)
                    <div class="lg:hidden px-4 py-2.5 border-t border-gray-100/70 bg-white/60">
                        {{ $header }}
                    </div>
                @endisset
            </header>

            <!-- Toast Alerts -->
            <div class="fixed top-4 inset-x-4 sm:inset-x-auto sm:right-5 sm:w-full sm:max-w-sm z-50 flex flex-col gap-3 pointer-events-none">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                         x-transition:enter="transform ease-out duration-300 transition"
                         x-transition:enter-start="translate-y-3 opacity-0 sm:translate-y-0 sm:translate-x-6"
                         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0 sm:translate-x-6"
                         class="pointer-events-auto overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-950/5">
                        <div class="flex items-start gap-3 p-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="flex-1 min-w-0 pt-0.5">
                                <p class="text-sm font-semibold text-gray-900">Berhasil</p>
                                <p class="mt-0.5 text-sm text-gray-500 break-words">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="flex-shrink-0 rounded-md text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="h-1 bg-green-500"></div>
                    </div>
                @endif
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         x-transition:enter="transform ease-out duration-300 transition"
                         x-transition:enter-start="translate-y-3 opacity-0 sm:translate-y-0 sm:translate-x-6"
                         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0 sm:translate-x-6"
                         class="pointer-events-auto overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-950/5">
                        <div class="flex items-start gap-3 p-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <div class="flex-1 min-w-0 pt-0.5">
                                <p class="text-sm font-semibold text-gray-900">Terjadi Kesalahan</p>
                                <p class="mt-0.5 text-sm text-gray-500 break-words">{{ session('error') }}</p>
                            </div>
                            <button @click="show = false" class="flex-shrink-0 rounded-md text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="h-1 bg-red-500"></div>
                    </div>
                @endif
                @if($errors->any())
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                         x-transition:enter="transform ease-out duration-300 transition"
                         x-transition:enter-start="translate-y-3 opacity-0 sm:translate-y-0 sm:translate-x-6"
                         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0 sm:translate-x-6"
                         class="pointer-events-auto overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-950/5">
                        <div class="flex items-start gap-3 p-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0 pt-0.5">
                                <p class="text-sm font-semibold text-gray-900">Input Tidak Valid</p>
                                <ul class="mt-1 list-disc pl-4 space-y-0.5 text-sm text-gray-500">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button @click="show = false" class="flex-shrink-0 rounded-md text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="h-1 bg-amber-500"></div>
                    </div>
                @endif
            </div>

            <!-- Page Slot -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

        <x-security-guard />
    </body>
</html>
