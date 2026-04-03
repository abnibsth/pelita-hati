<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Balita extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'birth_date',
        'gender',
        'mother_name',
        'mother_nik',
        'father_name',
        'parent_phone',
        'address',
        'rt_rw',
        'posyandu_id',
        'user_id',
        'status',
        'registration_date',
        'age_months',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'registration_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function orangtua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pertumbuhanRecords(): HasMany
    {
        return $this->hasMany(PertumbuhanRecord::class);
    }

    public function imunisasiRecords(): HasMany
    {
        return $this->hasMany(ImunisasiRecord::class);
    }

    public function vitaminRecords(): HasMany
    {
        return $this->hasMany(VitaminRecord::class);
    }

    public function kehadirans(): HasMany
    {
        return $this->hasMany(Kehadiran::class);
    }

    public function rujukans(): HasMany
    {
        return $this->hasMany(Rujukan::class);
    }

    // Accessors
    public function getAgeAttribute(): int
    {
        // Return stored age_months if available, otherwise calculate from birth_date
        return $this->age_months ?? $this->birth_date->diffInMonths(now());
    }

    public function getAgeInYearsAttribute(): float
    {
        return $this->birth_date->diffInYears(now());
    }

    // Scopes
    public function scopeAktif($query): void
    {
        $query->where('status', 'aktif');
    }

    public function scopeStunting($query): void
    {
        $query->whereHas('pertumbuhanRecords', function ($q) {
            $q->where('status_gizi', 'stunting')
                ->orderBy('tanggal', 'desc')
                ->limit(1);
        });
    }

    public function scopeGiziBuruk($query): void
    {
        $query->whereHas('pertumbuhanRecords', function ($q) {
            $q->where('status_gizi', 'gizi_buruk')
                ->orderBy('tanggal', 'desc')
                ->limit(1);
        });
    }
}
