<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KecamatanSeeder::class,
            KelurahanSeeder::class,
            PuskesmasSeeder::class,
            PosyanduSeeder::class,
            UserSeeder::class,
            BalitaSeeder::class,
            ImunisasiSeeder::class,
        ]);
    }
}
