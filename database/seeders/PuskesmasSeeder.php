<?php

namespace Database\Seeders;

use App\Models\Puskesmas;
use Illuminate\Database\Seeder;

class PuskesmasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $puskesmas = [
            [
                'name' => 'Puskesmas Gambir',
                'code' => 'PUS-317101',
                'kecamatan_id' => 1,
                'address' => 'Jl. Kebon Sirih No. 1, Gambir',
                'phone' => '021-3600001',
            ],
            [
                'name' => 'Puskesmas Tanah Abang',
                'code' => 'PUS-317102',
                'kecamatan_id' => 2,
                'address' => 'Jl. K.H. Mas Mansyur No. 1, Tanah Abang',
                'phone' => '021-3600002',
            ],
            [
                'name' => 'Puskesmas Menteng',
                'code' => 'PUS-317103',
                'kecamatan_id' => 3,
                'address' => 'Jl. Pegangsaan Timur No. 1, Menteng',
                'phone' => '021-3600003',
            ],
        ];

        foreach ($puskesmas as $p) {
            Puskesmas::create($p);
        }
    }
}
