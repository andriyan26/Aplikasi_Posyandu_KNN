<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6 opacity-90" :status="session('status')" />

    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-white mb-2 drop-shadow-md">Selamat Datang</h2>
        <p class="text-teal-100 text-sm font-medium">Silakan masuk untuk melanjutkan</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-teal-200 group-focus-within:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                </svg>
            </div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Alamat Email" 
                class="block w-full pl-10 pr-3 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-teal-100/60 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent focus:bg-white/20 transition-all duration-300 backdrop-blur-sm shadow-inner" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-200 drop-shadow-md" />
        </div>

        <!-- Password -->
        <div class="relative group mt-4">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-teal-200 group-focus-within:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Kata Sandi"
                class="block w-full pl-10 pr-3 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-teal-100/60 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent focus:bg-white/20 transition-all duration-300 backdrop-blur-sm shadow-inner" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-200 drop-shadow-md" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-6">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <div class="relative flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 border-2 border-teal-300 rounded bg-white/10 text-teal-400 shadow-sm focus:ring-teal-400 focus:ring-offset-0 transition duration-200 cursor-pointer">
                </div>
                <span class="ms-3 text-sm font-medium text-teal-50 group-hover:text-white transition-colors">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-teal-200 hover:text-white transition-colors underline decoration-white/30 hover:decoration-white underline-offset-4" href="{{ route('password.request') }}">
                    {{ __('Lupa Sandi?') }}
                </a>
            @endif
        </div>

        <div class="mt-8">
            <button type="submit" class="relative group w-full flex justify-center py-3 px-4 border border-transparent font-bold rounded-xl text-white bg-gradient-to-r from-teal-500 to-blue-500 hover:from-teal-400 hover:to-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-teal-400 shadow-[0_0_20px_rgba(45,212,191,0.4)] hover:shadow-[0_0_25px_rgba(45,212,191,0.6)] transform transition-all duration-300 active:scale-[0.98] overflow-hidden">
                <!-- Shine effect -->
                <div class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-20 group-hover:translate-x-full transform -translate-x-full transition-all duration-700 skew-x-12"></div>
                <span class="relative z-10 flex items-center gap-2">
                    {{ __('MASUK SEKARANG') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </span>
            </button>
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-xs text-teal-100/50">Gunakan akun admin@posyandu.test untuk mengelola.</p>
        </div>
    </form>
</x-guest-layout>
