<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rujukan extends Model
{
    use HasFactory;

    protected $fillable = [
        'balita_id',
        'puskesmas_id',
        'tanggal_rujuk',
        'jenis_keluhan',
        'status_gizi',
        'tindak_lanjut',
        'nakes_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_rujuk' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function balita(): BelongsTo
    {
        return $this->belongsTo(Balita::class);
    }

    public function puskesmas(): BelongsTo
    {
        return $this->belongsTo(Puskesmas::class);
    }

    public function nakes(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nakes_id');
    }
}
