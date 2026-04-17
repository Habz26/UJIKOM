@extends('layouts.guest')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Lupa Password?</h2>
        <p class="text-gray-600">Masukkan email Anda dan kami akan kirimkan link reset password.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input id="email" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('email') border-red-500 @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus />
            @error('email')
                <p class="mt-2 text-sm text-red-600 bg-red-50 p-2 rounded">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">← Kembali ke Login</a>
            <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                Kirim Link Reset
            </button>
        </div>
    </form>

    <div class="mt-6 text-center text-sm text-gray-500">
        <p>Hanya untuk email: @gmail.com, @yahoo.com, @outlook.com</p>
    </div>
@endsection