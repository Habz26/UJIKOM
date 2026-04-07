<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Sistem Peminjaman Buku')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="flex min-h-screen items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div>
                <div class="mx-auto h-20 w-20 flex items-center justify-center rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg">
                    <i class="bi bi-shield-lock-fill text-white text-3xl"></i>
                </div>
                <h2 class="mt-8 text-center text-3xl font-bold tracking-tight text-gray-900">@yield('title', 'Selamat Datang')</h2>
                <p class="mt-2 text-center text-sm text-gray-600">Sistem Perpustakaan Digital</p>
            </div>
            <div class="bg-white shadow-2xl rounded-3xl p-8 space-y-6">
                @yield('content')
            </div>
            <div class="text-center text-sm text-gray-500">
                © {{ date('Y') }} Sistem Peminjaman Buku. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
