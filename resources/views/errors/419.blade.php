<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 - Sesi Telah Berakhir | Portal Magang BPJS Ketenagakerjaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white/90 backdrop-blur-xl border border-gray-200/80 rounded-3xl p-8 sm:p-10 shadow-xl text-center">
        <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">419</h1>
        <h2 class="text-lg font-bold text-gray-800 mb-2">Sesi Telah Kedaluwarsa</h2>
        <p class="text-sm text-gray-500 mb-6 leading-relaxed">Sesi browser Anda telah berakhir karena tidak aktif beberapa saat. Silakan muat ulang halaman atau login kembali.</p>
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-white shadow-md shadow-green-200 transition-all" style="background: linear-gradient(135deg, #16a34a, #15803d);">
            Muat Ulang Halaman
        </a>
    </div>
</body>
</html>
