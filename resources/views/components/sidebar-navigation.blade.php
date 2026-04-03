@php
$userRole = auth()->user()->role;

$routes = match($userRole) {
    'admin_kota' => [
        'dashboard' => 'admin-kota.dashboard',
        'kecamatan' => 'admin-kota.kecamatan.index',
        'kelurahan' => 'admin-kota.kelurahan.index',
        'posyandu' => 'admin-kota.posyandu.index',
        'users' => 'admin-kota.users.index',
        'balita' => 'admin-kota.balita.index',
    ],
    'admin_kecamatan' => [
        'dashboard' => 'admin-kecamatan.dashboard',
        'kelurahan' => 'admin-kecamatan.kelurahan.index',
        'posyandu' => 'admin-kecamatan.posyandu.index',
        'users' => 'admin-kecamatan.users.index',
        'balita' => 'admin-kecamatan.balita.index',
    ],
    'admin_kelurahan' => [
        'dashboard' => 'admin-kelurahan.dashboard',
        'kelurahan' => 'admin-kelurahan.kelurahan.index',
        'posyandu' => 'admin-kelurahan.posyandu.index',
        'users' => 'admin-kelurahan.users.index',
        'balita' => 'admin-kelurahan.balita.index',
    ],
    'nakes_puskesmas' => [
        'dashboard' => 'nakes.dashboard',
        'rujukan' => 'nakes.rujukan.index',
        'imunisasi' => 'nakes.imunisasi.index',
        'balita' => 'nakes.balita.index',
    ],
    'orangtua' => [
        'dashboard' => 'orangtua.dashboard',
        'anak' => 'orangtua.anak.index',
        'profile' => 'orangtua.profile.edit',
    ],
    default => [
        'dashboard' => 'kader.dashboard',
        'posyandu' => 'kader.posyandu.show',
        'balita' => 'kader.balita.index',
        'pertumbuhan' => 'kader.pertumbuhan.index',
        'imunisasi' => 'kader.imunisasi.index',
        'kehadiran' => 'kader.kehadiran.index',
    ],
};

$canManageUsers = in_array($userRole, ['admin_kota', 'admin_kecamatan', 'admin_kelurahan']);
$canManageWilayah = in_array($userRole, ['admin_kota', 'admin_kecamatan']);
$isAdminKota = $userRole === 'admin_kota';
$isAdminKecamatan = $userRole === 'admin_kecamatan';
$isAdminKelurahan = $userRole === 'admin_kelurahan';
$isNakes = $userRole === 'nakes_puskesmas';
$isKader = $userRole === 'kader';
$isOrangtua = $userRole === 'orangtua';
@endphp

<div class="space-y-1">
    {{-- Dashboard --}}
    <a href="{{ route($routes['dashboard']) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs($routes['dashboard']) ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span>Dashboard</span>
    </a>
    
    @if($canManageWilayah)
    {{-- Data Wilayah Section --}}
    <p class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">Data Wilayah</p>
    
    @if($isAdminKota)
    <a href="{{ route($routes['kecamatan']) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin-kota.kecamatan.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
        <span>Data Kecamatan</span>
    </a>
    @endif
    
    <a href="{{ route($routes['kelurahan']) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('*.kelurahan.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
        <span>Data Kelurahan</span>
    </a>
    
    <a href="{{ route($routes['posyandu']) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('*.posyandu.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        <span>Data Posyandu</span>
    </a>
    @endif
    
    @if($isNakes)
    <p class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">Layanan Kesehatan</p>
    
    <a href="{{ route($routes['rujukan']) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('nakes.rujukan.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
        <span>Rujukan</span>
    </a>
    
    <a href="{{ route($routes['imunisasi']) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('nakes.imunisasi.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
        <span>Imunisasi</span>
    </a>
    @endif
    
    @if($isKader)
    <p class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">Kegiatan Posyandu</p>
    
    <a href="{{ route('kader.posyandu.show') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('kader.posyandu.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        <span>Posyandu Saya</span>
    </a>
    
    <a href="{{ route('kader.kehadiran.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('kader.kehadiran.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
        <span>Kehadiran</span>
    </a>
    @endif
    
    @if($isOrangtua)
    <p class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">Anak Saya</p>

    <a href="{{ route($routes['anak']) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('orangtua.anak.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        <span>Data Anak</span>
    </a>

    <a href="{{ route($routes['profile']) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('orangtua.profile.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        <span>Profil Saya</span>
    </a>
    @endif
    
    {{-- Management Section --}}
    @if($canManageUsers || $canManageWilayah)
    <p class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">Management</p>
    @endif
    
    @if($canManageUsers)
    <a href="{{ route($routes['users']) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('*.users.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <span>Manage User</span>
    </a>
    @endif

    @if(!$isOrangtua)
    <a href="{{ route($routes['balita']) }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('*.balita.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        <span>Data Balita</span>
    </a>
    @endif
</div>
