<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Kesalahan Server | Portal Magang BPJS Ketenagakerjaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white/90 backdrop-blur-xl border border-gray-200/80 rounded-3xl p-8 sm:p-10 shadow-xl text-center">
        <div class="w-16 h-16 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">500</h1>
        <h2 class="text-lg font-bold text-gray-800 mb-2">Terjadi Kesalahan Server</h2>
        <p class="text-sm text-gray-500 mb-6 leading-relaxed">Terjadi kendala pada sistem kami. Tim teknis telah menerima laporan dan sedang menangani masalah ini. Silakan coba kembali beberapa saat lagi.</p>
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-white shadow-md shadow-green-200 transition-all" style="background: linear-gradient(135deg, #16a34a, #15803d);">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
