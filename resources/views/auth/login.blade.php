@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    @if (session('status'))
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="text-blue-800 text-sm">{{ session('status') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
            <input id="email" type="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 ring-2 ring-red-200 @enderror"
                name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="masukkan@email.com">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6 relative">
            <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi</label>
            <div class="relative">
                <input id="password" type="password"
                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('password') border-red-500 ring-2 ring-red-200 @enderror"
                    name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi">
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword()">
                    <i class="bi bi-eye text-gray-400 hover:text-gray-600 transition-colors" id="toggleIcon"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-8 flex items-center">
            <input id="remember_me" type="checkbox"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('remember') ring-2 ring-red-200 @enderror"
                name="remember">
            <label for="remember_me" class="ml-2 block text-sm text-gray-600 select-none">Ingat saya</label>
        </div>

        <div class="space-y-4">
            @if (Route::has('password.request'))
                <div class="text-right">
                    <a href="{{ route('password.request') }}"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                        Lupa kata sandi?
                    </a>
                </div>
            @endif

            <button type="submit"
                class="group w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-xl font-bold text-lg shadow-xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                <span class="flex items-center justify-center">
                    <i class="bi bi-box-arrow-in-right me-2 group-hover:translate-x-1 transition-transform"></i>
                    Masuk ke Sistem
                </span>
            </button>
        </div>
    </form>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
@endsection
