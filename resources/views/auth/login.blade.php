<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    {{-- @vite('resources/css/app.css')
    @vite('resources/js/app.js') --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <div class="min-h-screen bg-gray-100 text-gray-900 flex items-center justify-center py-12">
        <div class="max-w-4xl w-full bg-white shadow-lg rounded-lg flex overflow-hidden">
            {{-- Bagian Form --}}
            <div class="w-full md:w-1/2 p-8">
                <h1 class="text-3xl font-bold text-center">Sign in</h1>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus class="mt-1 w-full" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" type="password" name="password" required class="mt-1 w-full" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</label>
                    </div>

                    <div class="flex items-center justify-between">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-primary-button class="ml-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>

                <p class="mt-6 text-xs text-gray-500 text-center">
                    By logging in, you agree to our
                    <a href="#" class="underline">Terms of Service</a>
                    and
                    <a href="#" class="underline">Privacy Policy</a>.
                </p>
            </div>

            {{-- Bagian Gambar --}}
            <div class="hidden md:flex md:w-1/2 bg-blue-900 items-center justify-center">
                <img src="{{ asset('img/pens.png') }}" alt="Logo" class="h-14">
            </div>
        </div>
    </div>
</body>
</html>
   

