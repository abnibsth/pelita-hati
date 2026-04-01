<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PertumbuhanRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'balita_id',
        'tanggal',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'lingkar_lengan_atas',
        'umur_saat_ukur',
        'status_gizi',
        'z_score_bbu',
        'z_score_tbu',
        'z_score_bbtb',
        'kader_id',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'berat_badan' => 'decimal:2',
            'tinggi_badan' => 'decimal:2',
            'lingkar_kepala' => 'decimal:2',
            'lingkar_lengan_atas' => 'decimal:2',
            'z_score_bbu' => 'decimal:2',
            'z_score_tbu' => 'decimal:2',
            'z_score_bbtb' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function balita(): BelongsTo
    {
        return $this->belongsTo(Balita::class);
    }

    public function kader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kader_id');
    }

    // Scopes
    public function scopeStunting($query): void
    {
        $query->where('status_gizi', 'stunting');
    }

    public function scopeGiziBuruk($query): void
    {
        $query->where('status_gizi', 'gizi_buruk');
    }

    public function scopeNormal($query): void
    {
        $query->where('status_gizi', 'normal');
    }
}
