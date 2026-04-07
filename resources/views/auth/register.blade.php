@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
            <input id="name" 
                   type="text" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('name') border-red-500 ring-2 ring-red-200 @enderror" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="name" 
                   placeholder="Nama lengkap Anda">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
            <input id="email" 
                   type="email" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 ring-2 ring-red-200 @enderror" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autocomplete="username" 
                   placeholder="masukkan@email.com">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6 relative">
            <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi</label>
            <div class="relative">
                <input id="password" 
                       type="password" 
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('password') border-red-500 ring-2 ring-red-200 @enderror" 
                       name="password" 
                       required 
                       autocomplete="new-password" 
                       placeholder="Kata sandi minimal 8 karakter">
                <button type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center" 
                        onclick="togglePassword('password', 'toggleIcon1')">
                    <i class="bi bi-eye text-gray-400 hover:text-gray-600 transition-colors" id="toggleIcon1"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Confirmation -->
        <div class="mb-8 relative">
            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
            <div class="relative">
                <input id="password_confirmation" 
                       type="password" 
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('password_confirmation') border-red-500 ring-2 ring-red-200 @enderror" 
                       name="password_confirmation" 
                       required 
                       autocomplete="new-password" 
                       placeholder="Ulangi kata sandi">
                <button type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center" 
                        onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                    <i class="bi bi-eye text-gray-400 hover:text-gray-600 transition-colors" id="toggleIcon2"></i>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-4">
            <a href="{{ route('login') }}" class="w-full block text-center bg-white border-2 border-gray-200 text-gray-800 py-4 px-6 rounded-xl font-bold text-lg hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-md">
                <span class="flex items-center justify-center">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Sudah Punya Akun? Masuk
                </span>
            </a>
            
            <button type="submit" class="group w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-xl font-bold text-lg shadow-xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                <span class="flex items-center justify-center">
                    <i class="bi bi-person-plus me-2 group-hover:translate-x-1 transition-transform"></i>
                    Daftar Akun Baru
                </span>
            </button>
        </div>
    </form>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            
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
