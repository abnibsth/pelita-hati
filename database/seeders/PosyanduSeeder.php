<?php

namespace Database\Seeders;

use App\Models\Posyandu;
use Illuminate\Database\Seeder;

class PosyanduSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posyandus = [
            // Kelurahan Gambir (ID: 1)
            [
                'name' => 'Posyandu Melati 1',
                'code' => 'POS-3171011001-001',
                'kelurahan_id' => 1,
                'address' => 'RT 001/001, Gambir',
                'jadwal_minggu_ke' => 1,
                'jadwal_hari' => 'Senin',
                'jadwal_jam_mulai' => '08:00',
                'jadwal_jam_selesai' => '11:00',
            ],
            [
                'name' => 'Posyandu Melati 2',
                'code' => 'POS-3171011001-002',
                'kelurahan_id' => 1,
                'address' => 'RT 005/002, Gambir',
                'jadwal_minggu_ke' => 2,
                'jadwal_hari' => 'Rabu',
                'jadwal_jam_mulai' => '08:00',
                'jadwal_jam_selesai' => '11:00',
            ],

            // Kelurahan Cideng (ID: 2)
            [
                'name' => 'Posyandu Anggrek 1',
                'code' => 'POS-3171011002-001',
                'kelurahan_id' => 2,
                'address' => 'RT 001/001, Cideng',
                'jadwal_minggu_ke' => 1,
                'jadwal_hari' => 'Selasa',
                'jadwal_jam_mulai' => '08:00',
                'jadwal_jam_selesai' => '11:00',
            ],
            [
                'name' => 'Posyandu Anggrek 2',
                'code' => 'POS-3171011002-002',
                'kelurahan_id' => 2,
                'address' => 'RT 003/002, Cideng',
                'jadwal_minggu_ke' => 3,
                'jadwal_hari' => 'Kamis',
                'jadwal_jam_mulai' => '08:00',
                'jadwal_jam_selesai' => '11:00',
            ],

            // Kelurahan Gelora (ID: 4)
            [
                'name' => 'Posyandu Mawar 1',
                'code' => 'POS-3171021001-001',
                'kelurahan_id' => 4,
                'address' => 'RT 001/001, Gelora',
                'jadwal_minggu_ke' => 1,
                'jadwal_hari' => 'Jumat',
                'jadwal_jam_mulai' => '08:00',
                'jadwal_jam_selesai' => '11:00',
            ],
        ];

        foreach ($posyandus as $posyandu) {
            Posyandu::create($posyandu);
        }
    }
}
