<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use Illuminate\Database\Seeder;

class KelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelurahans = [
            // Gambir
            ['name' => 'Gambir', 'code' => '3171011001', 'kecamatan_id' => 1, 'address' => 'Jl. Gambir Raya', 'phone' => '021-3500001'],
            ['name' => 'Cideng', 'code' => '3171011002', 'kecamatan_id' => 1, 'address' => 'Jl. Cideng Raya', 'phone' => '021-3500002'],
            ['name' => 'Petojo Utara', 'code' => '3171011003', 'kecamatan_id' => 1, 'address' => 'Jl. Petojo', 'phone' => '021-3500003'],
            
            // Tanah Abang
            ['name' => 'Gelora', 'code' => '3171021001', 'kecamatan_id' => 2, 'address' => 'Jl. Gelora', 'phone' => '021-3500004'],
            ['name' => 'Bendungan Hilir', 'code' => '3171021002', 'kecamatan_id' => 2, 'address' => 'Jl. Bendungan Hilir', 'phone' => '021-3500005'],
            ['name' => 'Karet Tengsin', 'code' => '3171021003', 'kecamatan_id' => 2, 'address' => 'Jl. Karet', 'phone' => '021-3500006'],
            
            // Menteng
            ['name' => 'Menteng', 'code' => '3171031001', 'kecamatan_id' => 3, 'address' => 'Jl. Menteng Raya', 'phone' => '021-3500007'],
            ['name' => 'Pegangsaan', 'code' => '3171031002', 'kecamatan_id' => 3, 'address' => 'Jl. Pegangsaan', 'phone' => '021-3500008'],
            ['name' => 'Cikini', 'code' => '3171031003', 'kecamatan_id' => 3, 'address' => 'Jl. Cikini', 'phone' => '021-3500009'],
        ];

        foreach ($kelurahans as $kelurahan) {
            Kelurahan::create($kelurahan);
        }
    }
}
