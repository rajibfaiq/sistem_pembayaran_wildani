<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Layanan Pembayaran TK/PAUD Wildani - Kelola pembayaran SPP, uang gedung, seragam, dan kebutuhan sekolah dengan mudah.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WILDANI - Sistem Informasi Layanan Pembayaran TK/PAUD')</title>

    {{-- Google Fonts - Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-800">
    @yield('content')

    {{-- Toast Container --}}
    <div id="toast-container"></div>
</body>
</html>
