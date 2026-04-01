<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Kota (Dinkes Jakarta)
        User::create([
            'name' => 'Admin Kota Jakarta',
            'email' => 'admin.kota@jakarta.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin_kota',
            'nik' => '3171010101010001',
            'phone' => '021-12345678',
            'email_verified_at' => now(),
        ]);

        // Admin Kecamatan Gambir
        User::create([
            'name' => 'Admin Kecamatan Gambir',
            'email' => 'admin.kec.gambir@jakarta.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin_kecamatan',
            'nik' => '3171010101010002',
            'phone' => '021-12345679',
            'kecamatan_id' => 1,
            'email_verified_at' => now(),
        ]);

        // Admin Kelurahan Gambir
        User::create([
            'name' => 'Admin Kelurahan Gambir',
            'email' => 'admin.kel.gambir@jakarta.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin_kelurahan',
            'nik' => '3171010101010003',
            'phone' => '021-12345680',
            'kelurahan_id' => 1,
            'email_verified_at' => now(),
        ]);

        // Admin Kelurahan Cideng
        User::create([
            'name' => 'Admin Kelurahan Cideng',
            'email' => 'admin.kel.cideng@jakarta.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin_kelurahan',
            'nik' => '3171010101010004',
            'phone' => '021-12345681',
            'kelurahan_id' => 2,
            'email_verified_at' => now(),
        ]);

        // Nakes Puskesmas Gambir
        User::create([
            'name' => 'Nakes Puskesmas Gambir',
            'email' => 'nakes.gambir@jakarta.go.id',
            'password' => Hash::make('password123'),
            'role' => 'nakes_puskesmas',
            'nik' => '3171010101010005',
            'phone' => '021-12345682',
            'puskesmas_id' => 1,
            'email_verified_at' => now(),
        ]);

        // Kader Posyandu Melati 1
        User::create([
            'name' => 'Kader Melati 1',
            'email' => 'kader.melati1@jakarta.go.id',
            'password' => Hash::make('password123'),
            'role' => 'kader',
            'nik' => '3171010101010006',
            'phone' => '021-12345683',
            'kelurahan_id' => 1,
            'posyandu_id' => 1,
            'email_verified_at' => now(),
        ]);

        // Kader Posyandu Melati 2
        User::create([
            'name' => 'Kader Melati 2',
            'email' => 'kader.melati2@jakarta.go.id',
            'password' => Hash::make('password123'),
            'role' => 'kader',
            'nik' => '3171010101010007',
            'phone' => '021-12345684',
            'kelurahan_id' => 1,
            'posyandu_id' => 2,
            'email_verified_at' => now(),
        ]);

        // Kader Posyandu Anggrek 1
        User::create([
            'name' => 'Kader Anggrek 1',
            'email' => 'kader.anggrek1@jakarta.go.id',
            'password' => Hash::make('password123'),
            'role' => 'kader',
            'nik' => '3171010101010008',
            'phone' => '021-12345685',
            'kelurahan_id' => 2,
            'posyandu_id' => 3,
            'email_verified_at' => now(),
        ]);
    }
}
