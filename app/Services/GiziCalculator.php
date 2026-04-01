<?php

namespace App\Services;

use App\Models\Balita;
use App\Models\PertumbuhanRecord;
use Carbon\Carbon;

/**
 * Service untuk menghitung status gizi balita berdasarkan standar WHO 2005
 */
class GiziCalculator
{
    /**
     * Hitung Z-Score dan status gizi berdasarkan antropometri WHO
     * 
     * @param float $beratBerat Badan dalam kg
     * @param float $tinggi Tinggi Badan dalam cm
     * @param int $ageMonths Umur dalam bulan
     * @param string $gender L atau P
     * @return array
     */
    public function calculate(
        float $beratBadan,
        float $tinggiBadan,
        int $ageMonths,
        string $gender
    ): array {
        // Dapatkan nilai referensi WHO
        $whoStandards = $this->getWhoStandards($ageMonths, $gender);
        
        // Hitung Z-Scores
        $zScoreBBU = $this->calculateZScoreBBU($beratBadan, $ageMonths, $gender);
        $zScoreTBU = $this->calculateZScoreTBU($tinggiBadan, $ageMonths, $gender);
        $zScoreBBTB = $this->calculateZScoreBBTB($beratBadan, $tinggiBadan, $gender);
        
        // Tentukan status gizi
        $statusGizi = $this->determineStatusGizi($zScoreBBU, $zScoreTBU, $zScoreBBTB);
        
        return [
            'z_score_bbu' => round($zScoreBBU, 2),
            'z_score_tbu' => round($zScoreTBU, 2),
            'z_score_bbtb' => round($zScoreBBTB, 2),
            'status_gizi' => $statusGizi,
            'is_stunting' => $zScoreTBU < -2,
            'is_wasting' => $zScoreBBTB < -2,
            'is_underweight' => $zScoreBBU < -2,
        ];
    }

    /**
     * Hitung Z-Score Berat Badan menurut Umur (BB/U)
     */
    private function calculateZScoreBBU(float $bb, int $ageMonths, string $gender): float
    {
        $standards = $this->getWhoStandardsBBU($ageMonths, $gender);
        return $this->calculateZScoreFromStandards($bb, $standards);
    }

    /**
     * Hitung Z-Score Tinggi Badan menurut Umur (TB/U)
     */
    private function calculateZScoreTBU(float $tb, int $ageMonths, string $gender): float
    {
        $standards = $this->getWhoStandardsTBU($ageMonths, $gender);
        return $this->calculateZScoreFromStandards($tb, $standards);
    }

    /**
     * Hitung Z-Score Berat Badan menurut Tinggi Badan (BB/TB)
     */
    private function calculateZScoreBBTB(float $bb, float $tb, string $gender): float
    {
        $standards = $this->getWhoStandardsBBTB($tb, $gender);
        return $this->calculateZScoreFromStandards($bb, $standards);
    }

    /**
     * Calculate Z-Score menggunakan LMS method (Lambda-Mu-Sigma)
     * Formula: Z = [(X/M)^L - 1] / (L * S)
     */
    private function calculateZScoreFromStandards(float $value, array $standards): float
    {
        if (empty($standards)) {
            return 0;
        }

        $L = $standards['L'] ?? 1;
        $M = $standards['M'] ?? $value;
        $S = $standards['S'] ?? 0.1;

        if ($L == 0) {
            return log($value / $M) / $S;
        }

        return (pow($value / $M, $L) - 1) / ($L * $S);
    }

    /**
     * Tentukan status gizi berdasarkan Z-Scores
     */
    private function determineStatusGizi(float $zScoreBBU, float $zScoreTBU, float $zScoreBBTB): string
    {
        // Deteksi stunting (TB/U < -2 SD)
        if ($zScoreTBU < -3) {
            return 'stunting'; // Severely stunted
        }

        // Deteksi gizi buruk (BB/TB < -3 SD atau BB/U < -3 SD)
        if ($zScoreBBTB < -3 || $zScoreBBU < -3) {
            return 'gizi_buruk';
        }

        // Deteksi gizi kurang
        if ($zScoreBBTB < -2 || $zScoreBBU < -2) {
            return 'kurang';
        }

        // Deteksi stunting moderate
        if ($zScoreTBU < -2) {
            return 'stunting';
        }

        // Deteksi gizi lebih
        if ($zScoreBBTB > 2 || $zScoreBBU > 2) {
            return 'lebih';
        }

        return 'normal';
    }

    /**
     * Dapatkan standar WHO untuk BB/U
     * Data disederhanakan untuk contoh - dalam implementasi nyata, 
     * gunakan tabel lengkap WHO 2005
     */
    private function getWhoStandardsBBU(int $ageMonths, string $gender): array
    {
        // Data referensi WHO 2005 (contoh untuk beberapa bulan)
        $standards = [
            'L' => [ // Laki-laki
                0 => ['L' => 1, 'M' => 3.3, 'S' => 0.1486],
                1 => ['L' => 1, 'M' => 4.5, 'S' => 0.1346],
                2 => ['L' => 1, 'M' => 5.6, 'S' => 0.1251],
                3 => ['L' => 1, 'M' => 6.4, 'S' => 0.1193],
                6 => ['L' => 1, 'M' => 7.9, 'S' => 0.1119],
                9 => ['L' => 1, 'M' => 8.9, 'S' => 0.1081],
                12 => ['L' => 1, 'M' => 9.6, 'S' => 0.1061],
                18 => ['L' => 1, 'M' => 10.9, 'S' => 0.1039],
                24 => ['L' => 1, 'M' => 12.2, 'S' => 0.1024],
                36 => ['L' => 1, 'M' => 14.3, 'S' => 0.1012],
                48 => ['L' => 1, 'M' => 16.3, 'S' => 0.1009],
                60 => ['L' => 1, 'M' => 18.3, 'S' => 0.1015],
            ],
            'P' => [ // Perempuan
                0 => ['L' => 1, 'M' => 3.2, 'S' => 0.1517],
                1 => ['L' => 1, 'M' => 4.2, 'S' => 0.1372],
                2 => ['L' => 1, 'M' => 5.1, 'S' => 0.1275],
                3 => ['L' => 1, 'M' => 5.8, 'S' => 0.1212],
                6 => ['L' => 1, 'M' => 7.3, 'S' => 0.1137],
                9 => ['L' => 1, 'M' => 8.2, 'S' => 0.1097],
                12 => ['L' => 1, 'M' => 8.9, 'S' => 0.1075],
                18 => ['L' => 1, 'M' => 10.2, 'S' => 0.1050],
                24 => ['L' => 1, 'M' => 11.5, 'S' => 0.1034],
                36 => ['L' => 1, 'M' => 13.9, 'S' => 0.1021],
                48 => ['L' => 1, 'M' => 16.0, 'S' => 0.1018],
                60 => ['L' => 1, 'M' => 18.2, 'S' => 0.1024],
            ],
        ];

        $genderData = $standards[$gender] ?? $standards['L'];
        
        // Cari data yang paling mendekati
        $closest = $this->findClosestAge($ageMonths, array_keys($genderData));
        return $genderData[$closest] ?? ['L' => 1, 'M' => 10, 'S' => 0.1];
    }

    /**
     * Dapatkan standar WHO untuk TB/U
     */
    private function getWhoStandardsTBU(int $ageMonths, string $gender): array
    {
        $standards = [
            'L' => [
                0 => ['L' => 1, 'M' => 49.9, 'S' => 0.0170],
                1 => ['L' => 1, 'M' => 54.7, 'S' => 0.0164],
                2 => ['L' => 1, 'M' => 58.4, 'S' => 0.0160],
                3 => ['L' => 1, 'M' => 61.4, 'S' => 0.0157],
                6 => ['L' => 1, 'M' => 67.6, 'S' => 0.0151],
                9 => ['L' => 1, 'M' => 72.0, 'S' => 0.0147],
                12 => ['L' => 1, 'M' => 75.7, 'S' => 0.0144],
                18 => ['L' => 1, 'M' => 82.3, 'S' => 0.0140],
                24 => ['L' => 1, 'M' => 87.1, 'S' => 0.0138],
                36 => ['L' => 1, 'M' => 95.1, 'S' => 0.0135],
                48 => ['L' => 1, 'M' => 102.7, 'S' => 0.0134],
                60 => ['L' => 1, 'M' => 110.0, 'S' => 0.0135],
            ],
            'P' => [
                0 => ['L' => 1, 'M' => 49.1, 'S' => 0.0171],
                1 => ['L' => 1, 'M' => 53.7, 'S' => 0.0165],
                2 => ['L' => 1, 'M' => 57.1, 'S' => 0.0161],
                3 => ['L' => 1, 'M' => 59.8, 'S' => 0.0158],
                6 => ['L' => 1, 'M' => 65.7, 'S' => 0.0152],
                9 => ['L' => 1, 'M' => 70.1, 'S' => 0.0148],
                12 => ['L' => 1, 'M' => 74.0, 'S' => 0.0145],
                18 => ['L' => 1, 'M' => 80.7, 'S' => 0.0141],
                24 => ['L' => 1, 'M' => 85.7, 'S' => 0.0139],
                36 => ['L' => 1, 'M' => 93.5, 'S' => 0.0136],
                48 => ['L' => 1, 'M' => 101.0, 'S' => 0.0135],
                60 => ['L' => 1, 'M' => 108.3, 'S' => 0.0136],
            ],
        ];

        $genderData = $standards[$gender] ?? $standards['L'];
        $closest = $this->findClosestAge($ageMonths, array_keys($genderData));
        return $genderData[$closest] ?? ['L' => 1, 'M' => 75, 'S' => 0.015];
    }

    /**
     * Dapatkan standar WHO untuk BB/TB
     */
    private function getWhoStandardsBBTB(float $tinggiBadan, string $gender): array
    {
        $standards = [
            'L' => [
                45 => ['L' => 1, 'M' => 2.5, 'S' => 0.1200],
                50 => ['L' => 1, 'M' => 3.2, 'S' => 0.1150],
                55 => ['L' => 1, 'M' => 4.2, 'S' => 0.1100],
                60 => ['L' => 1, 'M' => 5.3, 'S' => 0.1050],
                65 => ['L' => 1, 'M' => 6.5, 'S' => 0.1000],
                70 => ['L' => 1, 'M' => 7.8, 'S' => 0.0950],
                75 => ['L' => 1, 'M' => 9.2, 'S' => 0.0900],
                80 => ['L' => 1, 'M' => 10.6, 'S' => 0.0870],
                85 => ['L' => 1, 'M' => 11.8, 'S' => 0.0850],
                90 => ['L' => 1, 'M' => 13.0, 'S' => 0.0830],
                95 => ['L' => 1, 'M' => 14.1, 'S' => 0.0820],
                100 => ['L' => 1, 'M' => 15.3, 'S' => 0.0810],
                105 => ['L' => 1, 'M' => 16.5, 'S' => 0.0800],
                110 => ['L' => 1, 'M' => 17.8, 'S' => 0.0790],
            ],
            'P' => [
                45 => ['L' => 1, 'M' => 2.4, 'S' => 0.1250],
                50 => ['L' => 1, 'M' => 3.1, 'S' => 0.1200],
                55 => ['L' => 1, 'M' => 4.0, 'S' => 0.1150],
                60 => ['L' => 1, 'M' => 5.1, 'S' => 0.1100],
                65 => ['L' => 1, 'M' => 6.2, 'S' => 0.1050],
                70 => ['L' => 1, 'M' => 7.4, 'S' => 0.1000],
                75 => ['L' => 1, 'M' => 8.7, 'S' => 0.0950],
                80 => ['L' => 1, 'M' => 10.0, 'S' => 0.0920],
                85 => ['L' => 1, 'M' => 11.2, 'S' => 0.0900],
                90 => ['L' => 1, 'M' => 12.4, 'S' => 0.0880],
                95 => ['L' => 1, 'M' => 13.7, 'S' => 0.0870],
                100 => ['L' => 1, 'M' => 15.0, 'S' => 0.0860],
                105 => ['L' => 1, 'M' => 16.3, 'S' => 0.0850],
                110 => ['L' => 1, 'M' => 17.7, 'S' => 0.0840],
            ],
        ];

        $genderData = $standards[$gender] ?? $standards['L'];
        $closest = $this->findClosestHeight($tinggiBadan, array_keys($genderData));
        return $genderData[$closest] ?? ['L' => 1, 'M' => 12, 'S' => 0.09];
    }

    /**
     * Cari umur terdekat dalam standar
     */
    private function findClosestAge(int $ageMonths, array $availableAges): int
    {
        $closest = $availableAges[0];
        $minDiff = abs($ageMonths - $closest);

        foreach ($availableAges as $age) {
            $diff = abs($ageMonths - $age);
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $closest = $age;
            }
        }

        return $closest;
    }

    /**
     * Cari tinggi terdekat dalam standar
     */
    private function findClosestHeight(float $height, array $availableHeights): int
    {
        $closest = $availableHeights[0];
        $minDiff = abs($height - $closest);

        foreach ($availableHeights as $h) {
            $diff = abs($height - $h);
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $closest = $h;
            }
        }

        return $closest;
    }

    /**
     * Simpan record pertumbuhan baru
     */
    public function savePertumbuhan(
        Balita $balita,
        float $beratBadan,
        float $tinggiBadan,
        $kader,
        string $catatan = null
    ): PertumbuhanRecord {
        $ageMonths = $balita->birth_date->diffInMonths(now());
        
        $result = $this->calculate(
            $beratBadan,
            $tinggiBadan,
            $ageMonths,
            $balita->gender
        );

        return PertumbuhanRecord::create([
            'balita_id' => $balita->id,
            'tanggal' => now(),
            'berat_badan' => $beratBadan,
            'tinggi_badan' => $tinggiBadan,
            'umur_saat_ukur' => $this->formatAge($ageMonths),
            'status_gizi' => $result['status_gizi'],
            'z_score_bbu' => $result['z_score_bbu'],
            'z_score_tbu' => $result['z_score_tbu'],
            'z_score_bbtb' => $result['z_score_bbtb'],
            'kader_id' => $kader instanceof \App\Models\User ? $kader->id : $kader,
            'catatan' => $catatan,
        ]);
    }

    /**
     * Format umur dalam bulan menjadi string yang mudah dibaca
     */
    private function formatAge(int $months): string
    {
        if ($months < 12) {
            return "{$months} bulan";
        }
        
        $years = floor($months / 12);
        $remainingMonths = $months % 12;
        
        if ($remainingMonths == 0) {
            return "{$years} tahun";
        }
        
        return "{$years} tahun {$remainingMonths} bulan";
    }
}
