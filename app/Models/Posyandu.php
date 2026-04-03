<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Posyandu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'kelurahan_id',
        'address',
        'jadwal_minggu_ke',
        'jadwal_hari',
        'jadwal_jam_mulai',
        'jadwal_jam_selesai',
        'kader_koordinator_id',
    ];

    protected function casts(): array
    {
        return [
            'jadwal_minggu_ke' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function kaderKoordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kader_koordinator_id');
    }

    public function balitas(): HasMany
    {
        return $this->hasMany(Balita::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function kehadirans(): HasMany
    {
        return $this->hasMany(Kehadiran::class);
    }
}
