<x-guest-layout>
    <!-- Heading -->
    <img src="{{ asset('sbadmin_asset/img/logo_web.svg') }}" alt="Logo Login">

    <h1 class="text-2xl mt-3 font-weight-bold text-center mb-6">Selamat Datang di Sistem Informasi Pengelola Soal Budaya</h1>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Lupa Password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 ">
                {{ __('Log in') }}
            </x-primary-button>

            {{--    <a href="{{ route('register') }}" class="ml-3"><x-secondary-button>{{ __('Register') }}</x-secondary-button></a> --}}

        </div>
    </form>
</x-guest-layout>
