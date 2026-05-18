@extends('layouts.auth')

@section('title', 'Daftar — SiPosyandu Jakarta')

@section('form-header')
<div>
    <h2 class="text-[1.75rem] font-semibold text-zinc-950 tracking-tight leading-snug">Buat Akun Baru</h2>
    <p class="mt-1.5 text-zinc-500 text-sm leading-relaxed">Daftar sebagai Orang Tua untuk mengakses layanan SiPosyandu.</p>
</div>
@endsection

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    {{-- NIK --}}
    <div class="fade-in delay-1 space-y-1.5">
        <label for="nik" class="block text-[0.8125rem] font-medium text-zinc-700">Nomor Induk Kependudukan (NIK)</label>
        <input
            id="nik" name="nik" type="text"
            autocomplete="off" required maxlength="16" minlength="16"
            value="{{ old('nik') }}"
            placeholder="16 Digit NIK KTP Anda"
            class="form-input {{ $errors->has('nik') ? 'is-error' : '' }}"
            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
        >
        @error('nik')
        <p class="flex items-center space-x-1 text-xs text-red-500 mt-1">
            <svg style="width:12px;height:12px;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </p>
        @enderror
    </div>

    {{-- Name --}}
    <div class="fade-in delay-2 space-y-1.5">
        <label for="name" class="block text-[0.8125rem] font-medium text-zinc-700">Nama Lengkap</label>
        <input
            id="name" name="name" type="text"
            autocomplete="name" required
            value="{{ old('name') }}"
            placeholder="Budi Santoso"
            class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
        >
        @error('name')
        <p class="flex items-center space-x-1 text-xs text-red-500 mt-1">
            <svg style="width:12px;height:12px;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </p>
        @enderror
    </div>

    {{-- Email --}}
    <div class="fade-in delay-3 space-y-1.5">
        <label for="email" class="block text-[0.8125rem] font-medium text-zinc-700">Alamat Email</label>
        <input
            id="email" name="email" type="email"
            autocomplete="email" required
            value="{{ old('email') }}"
            placeholder="nama@jakarta.go.id"
            class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
        >
        @error('email')
        <p class="flex items-center space-x-1 text-xs text-red-500 mt-1">
            <svg style="width:12px;height:12px;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </p>
        @enderror
    </div>

    {{-- Password --}}
    <div class="fade-in delay-4 space-y-1.5">
        <label for="password" class="block text-[0.8125rem] font-medium text-zinc-700">Password</label>
        <input
            id="password" name="password" type="password"
            autocomplete="new-password" required
            placeholder="Minimal 8 karakter"
            class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
        >
        @error('password')
        <p class="flex items-center space-x-1 text-xs text-red-500 mt-1">
            <svg style="width:12px;height:12px;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </p>
        @enderror
    </div>

    {{-- Submit --}}
    <div class="fade-in delay-5 pt-3">
        <button type="submit" class="btn-submit">
            Daftar Sekarang
        </button>
    </div>
</form>

{{-- Login Prompt --}}
<div class="fade-in delay-6 mt-7 pt-6" style="border-top: 1px solid rgba(0,0,0,0.06);">
    <p class="text-sm text-zinc-500 text-center">
        Sudah memiliki akun?
        <br>
        <a href="{{ route('login') }}" class="inline-block mt-2 font-medium text-emerald-600 hover:text-emerald-700 transition-colors">
            Masuk di sini
        </a>
    </p>
</div>
@endsection
