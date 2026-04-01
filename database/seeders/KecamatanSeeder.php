<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Kecamatan in DKI Jakarta
        $kecamatans = [
            [
                'name' => 'Gambir',
                'code' => '317101',
                'address' => 'Jl. Kebon Sirih, Gambir, Jakarta Pusat',
                'phone' => '021-3440001',
            ],
            [
                'name' => 'Tanah Abang',
                'code' => '317102',
                'address' => 'Jl. K.H. Mas Mansyur, Tanah Abang, Jakarta Pusat',
                'phone' => '021-3440002',
            ],
            [
                'name' => 'Menteng',
                'code' => '317103',
                'address' => 'Jl. Pegangsaan Timur, Menteng, Jakarta Pusat',
                'phone' => '021-3440003',
            ],
            [
                'name' => 'Senen',
                'code' => '317104',
                'address' => 'Jl. Kramat Raya, Senen, Jakarta Pusat',
                'phone' => '021-3440004',
            ],
            [
                'name' => 'Cempaka Putih',
                'code' => '317105',
                'address' => 'Jl. Cempaka Putih Raya, Cempaka Putih, Jakarta Pusat',
                'phone' => '021-3440005',
            ],
        ];

        foreach ($kecamatans as $kecamatan) {
            Kecamatan::create($kecamatan);
        }
    }
}
