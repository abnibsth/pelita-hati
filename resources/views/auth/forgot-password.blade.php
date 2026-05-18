@extends('layouts.auth')

@section('title', 'Lupa Password — SiPosyandu Jakarta')

@section('form-header')
<div>
    <div class="w-12 h-12 rounded-2xl bg-zinc-100 border border-zinc-200 flex items-center justify-center mb-6">
        <svg class="w-6 h-6 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
    </div>
    <h2 class="text-3xl font-semibold text-zinc-950 tracking-tight">Lupa password?</h2>
    <p class="mt-2 text-zinc-500 text-sm leading-relaxed">Masukkan email terdaftar Anda. Kami akan kirimkan tautan untuk membuat password baru.</p>
</div>
@endsection

@section('content')

@if(session('success'))
    <div class="mb-6 flex items-start space-x-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-sm text-emerald-800">{{ session('success') }}</p>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium text-zinc-700 mb-1.5">Alamat Email</label>
        <input
            id="email" name="email" type="email"
            autocomplete="email" required
            value="{{ old('email') }}"
            placeholder="nama@jakarta.go.id"
            class="glass-input @error('email') error @enderror"
        >
        @error('email')
            <p class="mt-1.5 text-xs text-red-600 flex items-center space-x-1">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $message }}</span>
            </p>
        @enderror
    </div>

    <button type="submit"
        class="w-full py-3 px-6 rounded-xl bg-zinc-950 text-white text-sm font-medium tracking-tight hover:bg-zinc-800 transition-all active:scale-[0.99] shadow-lg shadow-zinc-900/10">
        Kirim Tautan Reset
    </button>

    <div class="text-center">
        <a href="{{ route('login') }}" class="text-sm text-zinc-400 hover:text-zinc-600 transition-colors">
            ← Kembali ke halaman masuk
        </a>
    </div>
</form>

@endsection
