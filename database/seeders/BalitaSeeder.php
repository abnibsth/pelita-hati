<?php

namespace Database\Seeders;

use App\Models\Balita;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Database\Seeder;

class BalitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get orangtua user
        $orangtua = User::where('email', 'orangtua@jakarta.go.id')->first();

        if ($orangtua) {
            // Create balita for orangtua
            Balita::create([
                'nik' => '3171010101010010',
                'name' => 'Ahmad Santoso',
                'birth_date' => now()->subMonths(12),
                'gender' => 'L',
                'mother_name' => 'Siti Aminah',
                'mother_nik' => '3171010101010009',
                'father_name' => 'Budi Santoso',
                'parent_phone' => '081234567890',
                'address' => 'Jl. Gambir Raya No. 1',
                'rt_rw' => '001/002',
                'posyandu_id' => 1,
                'user_id' => $orangtua->id,
                'status' => 'aktif',
                'registration_date' => now(),
            ]);

            Balita::create([
                'nik' => '3171010101010011',
                'name' => 'Fatimah Zahra',
                'birth_date' => now()->subMonths(24),
                'gender' => 'P',
                'mother_name' => 'Siti Aminah',
                'mother_nik' => '3171010101010009',
                'father_name' => 'Budi Santoso',
                'parent_phone' => '081234567890',
                'address' => 'Jl. Gambir Raya No. 1',
                'rt_rw' => '001/002',
                'posyandu_id' => 1,
                'user_id' => $orangtua->id,
                'status' => 'aktif',
                'registration_date' => now(),
            ]);
        }

        // Create some balitas for other posyandus
        $posyandu1 = Posyandu::find(1);
        $posyandu2 = Posyandu::find(2);

        if ($posyandu1) {
            Balita::create([
                'nik' => '3171010101010012',
                'name' => 'Muhammad Rizky',
                'birth_date' => now()->subMonths(18),
                'gender' => 'L',
                'mother_name' => 'Rina Wati',
                'mother_nik' => '3171010101010020',
                'father_name' => 'Ahmad Fauzi',
                'parent_phone' => '081234567891',
                'address' => 'Jl. Cideng Raya No. 5',
                'rt_rw' => '003/001',
                'posyandu_id' => $posyandu1->id,
                'status' => 'aktif',
                'registration_date' => now(),
            ]);
        }

        if ($posyandu2) {
            Balita::create([
                'nik' => '3171010101010013',
                'name' => 'Aisyah Putri',
                'birth_date' => now()->subMonths(15),
                'gender' => 'P',
                'mother_name' => 'Dewi Lestari',
                'mother_nik' => '3171010101010021',
                'father_name' => 'Yusuf Ibrahim',
                'parent_phone' => '081234567892',
                'address' => 'Jl. Petojo Raya No. 10',
                'rt_rw' => '002/003',
                'posyandu_id' => $posyandu2->id,
                'status' => 'aktif',
                'registration_date' => now(),
            ]);
        }
    }
}
