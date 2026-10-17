@php
    $isFixed = $fixed || request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('password.request');
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
    <body class="font-sans text-gray-900 antialiased bg-gray-50 selection:bg-green-500 selection:text-white {{ $isFixed ? 'h-screen h-[100dvh] overflow-hidden overscroll-none' : 'min-h-screen min-h-[100dvh]' }}">
        <div class="{{ $isFixed ? 'h-full flex overflow-hidden' : 'min-h-screen min-h-[100dvh] flex' }}">
            <!-- Left Side: Visual / Branding -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-green-700 to-green-900 items-center justify-center relative overflow-hidden h-full">
                <!-- Abstract Background Elements -->
                <div class="absolute top-0 left-0 w-full h-full opacity-10">
                    <svg class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2" width="800" height="800" viewBox="0 0 800 800" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="400" cy="400" r="400" fill="white"/>
                    </svg>
                </div>
                
                <div class="relative z-10 px-12 text-center text-white">
                    <img src="{{ asset('images/logo bpjstk.png') }}" alt="Logo BPJS Ketenagakerjaan" class="h-28 mx-auto mb-8 drop-shadow-lg object-contain mix-blend-multiply">
                    <h1 class="text-4xl font-bold mb-4 tracking-tight">Portal Magang Resmi</h1>
                    <p class="text-green-100 text-lg max-w-md mx-auto">Sistem terpadu untuk pencatatan absensi, pengelolaan tugas, dan pelaporan kegiatan peserta magang BPJS Ketenagakerjaan.</p>
                </div>
            </div>

            <!-- Right Side: Form Content -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-4 py-3 sm:px-8 sm:py-6 relative {{ $isFixed ? 'h-full overflow-hidden overscroll-none' : 'min-h-screen min-h-[100dvh] overflow-y-auto' }}">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-3 sm:mb-5 shrink-0">
                    <a href="/">
                        <img src="{{ asset('images/logo bpjstk.png') }}" alt="Logo BPJS" class="h-12 sm:h-16 mx-auto object-contain mix-blend-multiply">
                    </a>
                </div>

                <div class="w-full max-w-md shrink-0">
                    <!-- The Form Area -->
                    <div class="bg-white/80 backdrop-blur-xl shadow-xl sm:shadow-2xl rounded-2xl sm:rounded-3xl p-5 sm:p-8 border border-gray-100 relative">
                        <!-- Decorative top accent -->
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-1/3 h-1 bg-green-500 rounded-b-lg"></div>
                        
                        {{ $slot }}
                    </div>
                </div>
                
                <p class="mt-3 sm:mt-4 text-[11px] sm:text-xs text-gray-500 text-center shrink-0">
                    &copy; {{ date('Y') }} BPJS Ketenagakerjaan
                </p>
            </div>
        </div>

        <x-security-guard />
    </body>
</html>
