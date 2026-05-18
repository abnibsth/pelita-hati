@extends('layouts.auth')

@section('title', 'Portal Petugas — SiPosyandu Jakarta')

@section('form-header')
<div>
    <h2 class="text-[1.75rem] font-semibold text-zinc-950 tracking-tight leading-snug">Portal Petugas</h2>
    <p class="mt-1.5 text-zinc-500 text-sm leading-relaxed">Masuk ke dasbor manajemen khusus Admin, Nakes, dan Kader.</p>
</div>
@endsection

@section('content')

{{-- Flash: success --}}
@if(session('success'))
<div class="fade-in delay-1 mb-5 flex items-start space-x-3 rounded-xl px-4 py-3"
     style="background: #f0fdf4; border: 1px solid #bbf7d0;">
    <svg style="width:16px;height:16px;flex-shrink:0;margin-top:2px;" fill="none" stroke="#16a34a" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    <p class="text-sm text-emerald-800">{{ session('success') }}</p>
</div>
@endif

<form method="POST" action="{{ url('/petugas/login') }}" class="space-y-4">
    @csrf

    {{-- Email --}}
    <div class="fade-in delay-2 space-y-1.5">
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
    <div class="fade-in delay-3 space-y-1.5">
        <div class="flex items-center justify-between">
            <label for="password" class="block text-[0.8125rem] font-medium text-zinc-700">Password</label>
            <a href="{{ route('password.request') }}"
               class="text-xs text-emerald-600 hover:text-emerald-700 transition-colors font-medium">
                Lupa password?
            </a>
        </div>
        <input
            id="password" name="password" type="password"
            autocomplete="current-password" required
            placeholder="••••••••"
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

    {{-- Remember --}}
    <div class="fade-in delay-4 flex items-center space-x-2.5 pt-0.5">
        <input id="remember" name="remember" type="checkbox"
               class="w-4 h-4 rounded border-zinc-300 text-emerald-600 cursor-pointer"
               style="accent-color: #10b981;">
        <label for="remember" class="text-sm text-zinc-600 cursor-pointer select-none">Ingat saya 30 hari</label>
    </div>

    {{-- Submit --}}
    <div class="fade-in delay-5 pt-1">
        <button type="submit" class="btn-submit">
            Masuk ke Dasbor
        </button>
    </div>
</form>

{{-- Demo accounts --}}
<div class="fade-in delay-6 mt-7 pt-6" style="border-top: 1px solid rgba(0,0,0,0.06);">
    <p class="text-[11px] font-semibold text-zinc-400 uppercase tracking-widest mb-3">Akun Demo Petugas</p>
    <div class="space-y-1.5">
        @php
        $demos = [
            ['role' => 'Admin Kota',      'email' => 'admin.kota@jakarta.go.id'],
            ['role' => 'Admin Kecamatan', 'email' => 'admin.kecamatan@jakarta.go.id'],
            ['role' => 'Admin Kelurahan', 'email' => 'admin.kelurahan@jakarta.go.id'],
            ['role' => 'Nakes',           'email' => 'nakes@jakarta.go.id'],
            ['role' => 'Kader',           'email' => 'kader.melati1@jakarta.go.id'],
        ];
        @endphp
        @foreach($demos as $demo)
        <div class="flex items-center justify-between rounded-xl px-3.5 py-2.5"
             style="background: rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05);">
            <span class="text-xs font-medium text-zinc-700">{{ $demo['role'] }}</span>
            <span class="mono text-[11px] text-zinc-400">{{ $demo['email'] }}</span>
        </div>
        @endforeach
    </div>
    <p class="text-[11px] text-zinc-400 mt-2.5 text-center">
        Semua akun: <span class="mono font-medium text-zinc-600">password123</span>
    </p>
</div>

@endsection
