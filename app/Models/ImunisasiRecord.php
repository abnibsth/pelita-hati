<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImunisasiRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'balita_id',
        'jenis_imunisasi',
        'tanggal_diberikan',
        'batch_number',
        'lokasi',
        'input_by',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_diberikan' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function balita(): BelongsTo
    {
        return $this->belongsTo(Balita::class);
    }

    public function inputBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'input_by');
    }
}
