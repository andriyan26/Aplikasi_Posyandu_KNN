<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Posyandu Belimbing') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('assets/Logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            // Hard set the theme immediately to prevent any white flash or delay
            const storedTheme = localStorage.getItem('theme');
            if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased h-full overflow-hidden text-slate-800 dark:text-slate-200 transition-colors duration-300" x-data="{ sidebarOpen: false }">
        
        <div class="flex h-screen bg-slate-50 dark:bg-slate-900 transition-colors">
            <!-- Sidebar (Include) -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-slate-50 dark:bg-slate-900 relative transition-colors duration-300">
                
                <!-- Header Background Area -->
                <div class="bg-blue-600 dark:bg-slate-900 pb-32 pt-2 px-4 sm:px-6 lg:px-8 transition-colors">
                    <!-- Top Navigation Bar (Inside Blue) -->
                    <header class="flex items-center justify-between h-16 text-white mb-4 border-b border-white/20 pb-2">
                        <!-- Left Side: Hamburger & Brand Name -->
                        <div class="flex flex-1 items-center gap-4">
                            <button @click="sidebarOpen = true" class="md:hidden p-1 rounded-md text-white hover:bg-white/10 focus:outline-none">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </button>
                            <h1 class="text-xl sm:text-2xl font-semibold tracking-wide flex items-center gap-3 uppercase">
                                <img src="{{ asset('assets/Logo.png') }}" class="w-8 h-8 object-contain drop-shadow-md" alt="Logo">
                                POSYANDU BELIMBING
                            </h1>
                        </div>

                        <!-- Right Side: Topbar Profile Dropdown -->
                        <div class="flex items-center shrink-0 gap-4" x-data="{ 
                            darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                            toggleTheme() {
                                this.darkMode = !this.darkMode;
                                if (this.darkMode) {
                                    document.documentElement.classList.add('dark');
                                    localStorage.theme = 'dark';
                                } else {
                                    document.documentElement.classList.remove('dark');
                                    localStorage.theme = 'light';
                                }
                            }
                        }">
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger">
                                    <button class="flex items-center p-1.5 rounded-full hover:bg-white/10 focus:outline-none transition-all group border border-white/10">
                                        <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center overflow-hidden">
                                            <span class="text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        </div>
                                        <svg class="h-4 w-4 ml-1 text-white opacity-60 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <div class="px-3 py-2 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-700">
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Masuk Sebagai</p>
                                        <p class="text-[11px] font-black text-slate-700 dark:text-slate-200 truncate">{{ Auth::user()->email }}</p>
                                    </div>
 
                                    <!-- Theme Selection -->
                                    <div class="px-1 py-1">
                                        <button @click.stop="toggleTheme(); $dispatch('close-dropdown')" class="w-full flex items-center px-2 py-1.5 text-[11px] font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors group">
                                            <div class="flex items-center w-full">
                                                <template x-if="!darkMode">
                                                    <div class="flex items-center w-full">
                                                        <svg style="width: 14px; height: 14px;" class="mr-2 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z"/></svg>
                                                        <span>Mode Terang</span>
                                                    </div>
                                                </template>
                                                <template x-if="darkMode">
                                                    <div class="flex items-center w-full">
                                                        <svg style="width: 14px; height: 14px;" class="mr-2 text-indigo-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                                                        <span class="text-indigo-400">Mode Gelap</span>
                                                    </div>
                                                </template>
                                            </div>
                                        </button>
                                    </div>
 
                                    <div class="border-t border-slate-100 dark:border-slate-800 my-0.5 mx-1"></div>
 
                                    <div class="px-1 py-1">
                                        <x-dropdown-link :href="route('profile.edit')" class="!p-0 border-none">
                                            <div class="flex items-center px-2 py-1.5 text-[11px] font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all">
                                                <svg style="width: 14px; height: 14px;" class="mr-2 opacity-70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                <span>Pengaturan Akun</span>
                                            </div>
                                        </x-dropdown-link>
                                    </div>
 
                                    <div class="border-t border-slate-100 dark:border-slate-800 my-0.5 mx-1"></div>
 
                                    <div class="px-1 py-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center px-2 py-1.5 text-[11px] font-extrabold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all uppercase tracking-widest">
                                                <svg style="width: 14px; height: 14px;" class="mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                                Keluar
                                            </button>
                                        </form>
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </header>

                    <!-- Page Title (Slot) area -->
                    <div class="text-white mt-2 pb-8">
                        @if (isset($header))
                            <h2 class="text-2xl font-normal uppercase tracking-wide">
                                {{ $header }}
                            </h2>
                            <p class="text-blue-100 mt-1 text-sm font-medium">Panel Manajemen Aplikasi</p>
                        @endif
                    </div>
                </div>

                <!-- Main Content Slot (Overlapping blue header) -->
                <main class="-mt-24 px-4 sm:px-6 lg:px-8 pb-12 relative z-10 w-full max-w-full">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @if (isset($scripts))
            {{ $scripts }}
        @endif
    </body>
</html>
