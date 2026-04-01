@extends('layouts.auth')

@section('title', 'Login - SiPosyandu Jakarta')

@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf
    
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" autocomplete="email" required 
            class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('email') border-red-500 @enderror"
            value="{{ old('email') }}" placeholder="nama@example.com">
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required 
            class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('password') border-red-500 @enderror"
            placeholder="••••••••">
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox" 
                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
            <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
        </div>
        <a href="#" class="text-sm text-primary-600 hover:text-primary-700">Lupa password?</a>
    </div>

    <button type="submit" 
        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition">
        Masuk
    </button>
</form>

<div class="mt-6">
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Akun Demo</span>
        </div>
    </div>

    <div class="mt-4 space-y-2 text-xs text-gray-600">
        <p><strong>Admin Kota:</strong> admin.kota@jakarta.go.id / password123</p>
        <p><strong>Kader:</strong> kader.melati1@jakarta.go.id / password123</p>
    </div>
</div>
@endsection
