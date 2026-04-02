@extends('layouts.auth')

@section('title', 'Reset Password - SiPosyandu Jakarta')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Reset Password</h2>
    <p class="mt-2 text-sm text-gray-600">Buat password baru untuk akun Anda.</p>
</div>

<form method="POST" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" type="email" disabled 
            class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-600"
            value="{{ $email }}">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
        <input id="password" name="password" type="password" required 
            class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('password') border-red-500 @enderror"
            placeholder="Minimal 8 karakter">
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required 
            class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="Ulangi password baru">
    </div>

    <button type="submit" 
        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition">
        Reset Password
    </button>
</form>

<div class="mt-6 text-center">
    <a href="{{ route('login') }}" class="text-sm text-primary-600 hover:text-primary-700">
        ← Kembali ke halaman login
    </a>
</div>
@endsection
