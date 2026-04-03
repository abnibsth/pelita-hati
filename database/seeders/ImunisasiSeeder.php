<?php

namespace Database\Seeders;

use App\Models\Balita;
use App\Models\ImunisasiRecord;
use Illuminate\Database\Seeder;

class ImunisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get balita for imunisasi
        $balita = Balita::where('nik', '3171010101010010')->first();

        if ($balita) {
            // Create imunisasi records for Ahmad Santoso
            ImunisasiRecord::create([
                'balita_id' => $balita->id,
                'jenis_imunisasi' => 'BCG',
                'tanggal_diberikan' => now()->subMonths(11),
                'lokasi' => 'Posyandu',
                'keterangan' => 'Imunisasi pertama',
                'input_by' => 6, // Kader Melati 1
            ]);

            ImunisasiRecord::create([
                'balita_id' => $balita->id,
                'jenis_imunisasi' => 'HB-0',
                'tanggal_diberikan' => now()->subMonths(10),
                'lokasi' => 'Posyandu',
                'keterangan' => '',
                'input_by' => 6,
            ]);

            ImunisasiRecord::create([
                'balita_id' => $balita->id,
                'jenis_imunisasi' => 'Polio-1',
                'tanggal_diberikan' => now()->subMonths(9),
                'lokasi' => 'Posyandu',
                'keterangan' => '',
                'input_by' => 6,
            ]);
        }

        // Create imunisasi for second balita
        $balita2 = Balita::where('nik', '3171010101010011')->first();

        if ($balita2) {
            ImunisasiRecord::create([
                'balita_id' => $balita2->id,
                'jenis_imunisasi' => 'BCG',
                'tanggal_diberikan' => now()->subMonths(23),
                'lokasi' => 'Posyandu',
                'keterangan' => '',
                'input_by' => 6,
            ]);

            ImunisasiRecord::create([
                'balita_id' => $balita2->id,
                'jenis_imunisasi' => 'HB-0',
                'tanggal_diberikan' => now()->subMonths(22),
                'lokasi' => 'Posyandu',
                'keterangan' => '',
                'input_by' => 6,
            ]);
        }
    }
}
