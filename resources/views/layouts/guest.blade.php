@php
    $isFixed = (!empty($fixed)) || request()->routeIs('login') || request()->routeIs('password.request');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $isFixed ? 'h-full overflow-hidden' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
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
    <body class="font-sans text-gray-900 antialiased selection:bg-green-500 selection:text-white {{ $isFixed ? 'h-screen h-[100dvh] overflow-hidden overscroll-none' : 'min-h-screen min-h-[100dvh]' }} relative">
        
        <!-- Background Wallpaper Container (Full Screen Image with Overlay) -->
        <div class="fixed inset-0 w-full h-full -z-10 overflow-hidden pointer-events-none select-none">
            <img src="{{ asset('images/background_login.png') }}"
                 alt="Background BPJS Ketenagakerjaan"
                 class="w-full h-full object-cover object-left lg:object-center" />
            <!-- Dark Gradient Overlay for contrast -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/25 to-slate-900/50 backdrop-blur-[0.5px]"></div>
        </div>

        <div class="{{ $isFixed ? 'h-full flex overflow-hidden' : 'min-h-screen min-h-[100dvh] flex' }} relative z-10">
            <!-- Left Side: Visual / Branding Area (lets workers in the photo shine) -->
            <div class="hidden lg:flex lg:w-7/12 flex-col justify-center p-10 xl:p-16">
                <!-- Text only (vertically centered, no background box) -->
                <div class="max-w-xl text-white">
                    <h1 class="text-4xl xl:text-5xl font-extrabold tracking-tight mb-4 drop-shadow-[0_2px_8px_rgba(0,0,0,0.85)]">
                        Portal Magang Resmi
                    </h1>
                    <p class="text-base xl:text-lg text-white font-medium leading-relaxed drop-shadow-[0_2px_6px_rgba(0,0,0,0.85)] max-w-lg">
                        Sistem terpadu untuk pencatatan absensi, pengelolaan tugas, dan pelaporan kegiatan peserta magang.
                    </p>
                </div>
            </div>

            <!-- Right Side: Form Content -->
            <div class="w-full lg:w-5/12 flex flex-col justify-center items-center px-4 py-6 sm:px-8 relative {{ $isFixed ? 'h-full overflow-hidden overscroll-none' : 'min-h-screen min-h-[100dvh] overflow-y-auto' }}">
                <div class="w-full max-w-md shrink-0">
                    <!-- The Form Area -->
                    <div class="bg-white/95 backdrop-blur-xl shadow-2xl rounded-3xl p-6 sm:p-9 border border-white/80 relative">
                        <!-- Decorative top accent -->
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-1/3 h-1 bg-green-500 rounded-b-lg"></div>

                        <!-- Brand Logo for card (visible on all screens) -->
                        <div class="flex justify-center mb-6">
                            <img src="{{ asset('images/logo bpjstk.png') }}" alt="Logo BPJS Ketenagakerjaan" class="h-10 sm:h-12 object-contain">
                        </div>
                        
                        {{ $slot }}
                    </div>
                </div>
                
                <p class="mt-4 text-xs text-white/90 font-medium text-center drop-shadow shrink-0">
                    &copy; {{ date('Y') }} BPJS Ketenagakerjaan
                </p>
            </div>
        </div>

        <x-security-guard />
    </body>
</html>
