<!-- Sidebar Navigation (Light Theme / Unpam Style) -->
<nav class="shrink-0 flex items-start transition-all" x-data="{ open: false }">

    <!-- Mobile Screen Overlay -->
    <div x-show="sidebarOpen" 
         x-transition.opacity 
         class="fixed inset-0 z-40 bg-slate-900/80 md:hidden backdrop-blur-sm"
         @click="sidebarOpen = false"
         style="display: none;"></div>

    <!-- Sidebar Container -->
    <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" 
         class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 shadow-[4px_0_24px_rgba(0,0,0,0.02)] transition-transform duration-300 transform md:translate-x-0 md:static md:w-72 md:h-screen md:overflow-y-auto flex flex-col items-center border-r border-slate-100 dark:border-slate-800">
        
        <!-- Sidebar Header (Logo Profile Center) -->
        <div class="flex flex-col items-center justify-center w-full pt-10 pb-6 bg-white dark:bg-slate-900 relative">
            <div class="relative">
                <div class="h-24 w-24 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center font-bold text-4xl text-white shadow-lg overflow-hidden ring-4 ring-white">
                    <!-- Placeholder Profile Image or Initial -->
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <!-- Status Badge (Green dot like in image) -->
                <div class="absolute bottom-1 right-1 h-5 w-5 bg-green-500 border-2 border-white rounded-full"></div>
            </div>
            <div class="mt-4 text-center px-4">
                <h3 class="font-extrabold text-lg text-slate-800 dark:text-white uppercase tracking-wide">{{ Auth::user()->name }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-1 tracking-widest uppercase">{{ Auth::user()->role === 'admin' ? 'ADMINISTRATOR' : 'KADER POSYANDU' }}</p>
                <div class="flex items-center justify-center mt-2 text-xs font-bold text-green-600 bg-green-50 dark:bg-green-900/20 px-2.5 py-1 rounded-full border border-green-100 dark:border-green-800 uppercase">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    Aktif
                </div>
            </div>
        </div>

        <!-- Section Navigation Area -->
        <div class="w-full flex-1 px-4 space-y-1 overflow-y-auto mt-4 custom-scrollbar pb-10">

            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3.5 rounded-2xl transition duration-200 font-bold text-sm tracking-wide group {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                <svg class="h-5 w-5 mr-4 {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                BERANDA
            </a>
 
            <div class="w-full h-px bg-slate-100 dark:bg-slate-800 my-4"></div> <!-- Divider -->

            <a href="{{ route('balita.index') }}" class="flex items-center px-4 py-3.5 rounded-2xl transition duration-200 font-bold text-sm tracking-wide group {{ request()->routeIs('balita.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                <svg class="h-5 w-5 mr-4 {{ request()->routeIs('balita.*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                DATA BALITA
            </a>
 
            <a href="{{ route('pemeriksaan.index') }}" class="flex items-center px-4 py-3.5 rounded-2xl transition duration-200 font-bold text-sm tracking-wide group {{ request()->routeIs('pemeriksaan.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                <svg class="h-5 w-5 mr-4 {{ request()->routeIs('pemeriksaan.*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                PEMERIKSAAN
            </a>
 
            @if(Auth::user()->role === 'admin')
            <div class="w-full h-px bg-slate-100 dark:bg-slate-800 my-4"></div> <!-- Divider -->
            
            <div class="pt-2 pb-2">
                <p class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Administrator</p>
                <a href="{{ route('knn.evaluasi') }}" class="flex items-center px-4 py-3.5 rounded-2xl transition duration-200 font-bold text-sm tracking-wide group {{ request()->routeIs('knn.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                    <svg class="h-5 w-5 mr-4 {{ request()->routeIs('knn.*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    EVALUASI KNN
                </a>
 
                <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3.5 rounded-2xl transition duration-200 font-bold text-sm tracking-wide group mt-1 {{ request()->routeIs('users.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                    <svg class="h-5 w-5 mr-4 {{ request()->routeIs('users.*') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    PENGATURAN AKUN
                </a>
            </div>
            @endif
        </div>

    </div>
</nav>

<style>
/* Optional custom scrollbar for sidebar */
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 20px;
}
</style>
