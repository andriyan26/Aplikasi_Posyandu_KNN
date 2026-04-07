<x-app-layout>
    <x-slot name="header">
        Profil Akun
    </x-slot>

    <div class="py-12 relative z-20">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700/50 sm:rounded-[20px] transition-colors">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700/50 sm:rounded-[20px] transition-colors">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700/50 sm:rounded-[20px] transition-colors">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
