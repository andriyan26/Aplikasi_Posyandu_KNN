<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 overflow-hidden relative">
        <!-- VIDEO BACKGROUND PLACEHOLDER -->
        <div class="fixed inset-0 z-0 bg-gray-900">
            <!-- Video Latar Belakang -->
            <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover opacity-80">
                <source src="{{ asset('assets/video.mp4') }}" type="video/mp4">
            </video>

            <!-- Overlay gelap agar text tetap terbaca -->
            <div class="absolute inset-0 w-full h-full bg-indigo-900/40 mix-blend-multiply"></div>
            <div class="absolute inset-0 w-full h-full bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-6 transform transition hover:scale-105 duration-300">
                <a href="/" class="flex flex-col items-center group">
                    <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-md border border-white/20 shadow-xl group-hover:bg-white/20 transition duration-300">
                        <x-application-logo class="w-12 h-12 fill-current text-white/90 drop-shadow-lg" />
                    </div>
                    <h1 class="mt-4 text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-200 to-blue-200 tracking-wider uppercase drop-shadow-md">Posyandu Belimbing</h1>
                    <p class="mt-1 text-blue-100/80 font-medium tracking-wide text-xs">Pemantauan Gizi & Stunting Terpadu</p>
                </a>
            </div>

            <!-- Glassmorphism Card -->
            <div class="w-full sm:max-w-sm px-8 py-8 bg-white/10 backdrop-blur-xl shadow-[0_8px_32px_0_rgba(31,38,135,0.37)] border border-white/20 overflow-hidden sm:rounded-3xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
