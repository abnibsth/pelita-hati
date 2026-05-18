@extends('layouts.auth')

@section('title', 'Masuk — SiPosyandu Jakarta')

@section('form-header')
<div class="text-center sm:text-left">
    <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-zinc-100 border border-zinc-200 text-zinc-600 text-[10px] font-semibold uppercase tracking-widest mb-4">
        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <span>Portal Orang Tua</span>
    </div>
    <h2 class="text-2xl sm:text-3xl font-semibold text-zinc-950 tracking-tight leading-tight">Pantau Tumbuh<br/>Kembang Anak.</h2>
    <p class="mt-2 text-zinc-500 text-[13px] leading-relaxed">Masuk untuk melihat catatan imunisasi dan pertumbuhan balita Anda.</p>
</div>
@endsection

@section('content')

{{-- Flash: success --}}
@if(session('success'))
<div class="fade-in delay-1 mb-6 flex items-start space-x-3 rounded-lg px-4 py-3 bg-emerald-50 border border-emerald-100">
    <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    <p class="text-[13px] font-medium text-emerald-800">{{ session('success') }}</p>
</div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    {{-- Email --}}
    <div class="fade-in delay-2 space-y-1.5">
        <label for="email" class="block text-[13px] font-medium text-zinc-900">Alamat Email</label>
        <input
            id="email" name="email" type="email"
            autocomplete="email" required
            value="{{ old('email') }}"
            placeholder="nama@contoh.com"
            class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
        >
        @error('email')
        <p class="flex items-center space-x-1.5 text-xs text-red-500 mt-1.5 font-medium">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </p>
        @enderror
    </div>

    {{-- Password --}}
    <div class="fade-in delay-3 space-y-1.5">
        <div class="flex items-center justify-between">
            <label for="password" class="block text-[13px] font-medium text-zinc-900">Password</label>
            <a href="{{ route('password.request') }}"
               class="text-[12px] font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                Lupa sandi?
            </a>
        </div>
        <input
            id="password" name="password" type="password"
            autocomplete="current-password" required
            placeholder="••••••••"
            class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
        >
        @error('password')
        <p class="flex items-center space-x-1.5 text-xs text-red-500 mt-1.5 font-medium">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </p>
        @enderror
    </div>

    {{-- Remember --}}
    <div class="fade-in delay-4 flex items-center space-x-2.5 pt-1">
        <input id="remember" name="remember" type="checkbox"
               class="w-4 h-4 rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 cursor-pointer">
        <label for="remember" class="text-[13px] font-medium text-zinc-600 cursor-pointer select-none">Ingat saya di perangkat ini</label>
    </div>

    {{-- Submit --}}
    <div class="fade-in delay-5 pt-3">
        <button type="submit" class="btn-submit btn-submit-emerald flex justify-center items-center">
            <span>Masuk ke Dasbor</span>
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
    </div>
</form>

{{-- Registration Prompt --}}
<div class="fade-in delay-6 mt-8 pt-6 border-t border-zinc-100 text-center">
    <p class="text-[13px] text-zinc-500">
        Belum memiliki akun SiPosyandu?
        <a href="{{ route('register') }}" class="font-semibold text-zinc-900 hover:text-emerald-600 transition-colors ml-1">
            Daftar Sekarang
        </a>
    </p>
</div>

@endsection
