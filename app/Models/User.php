<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nik',
        'phone',
        'photo_path',
        'kelurahan_id',
        'kecamatan_id',
        'posyandu_id',
        'puskesmas_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Scopes
    public function scopeAdminKota($query): void
    {
        $query->where('role', 'admin_kota');
    }

    public function scopeAdminKecamatan($query): void
    {
        $query->where('role', 'admin_kecamatan');
    }

    public function scopeAdminKelurahan($query): void
    {
        $query->where('role', 'admin_kelurahan');
    }

    public function scopeNakesPuskesmas($query): void
    {
        $query->where('role', 'nakes_puskesmas');
    }

    public function scopeKader($query): void
    {
        $query->where('role', 'kader');
    }

    public function scopeOrangtua($query): void
    {
        $query->where('role', 'orangtua');
    }

    // Relationships
    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function puskesmas(): BelongsTo
    {
        return $this->belongsTo(Puskesmas::class);
    }

    public function balitas(): HasMany
    {
        return $this->hasMany(Balita::class);
    }

    public function pertumbuhanRecords(): HasMany
    {
        return $this->hasMany(PertumbuhanRecord::class, 'kader_id');
    }

    public function imunisasiRecords(): HasMany
    {
        return $this->hasMany(ImunisasiRecord::class, 'input_by');
    }

    public function rujukans(): HasMany
    {
        return $this->hasMany(Rujukan::class, 'nakes_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // Accessors
    public function getIsAdminKotaAttribute(): bool
    {
        return $this->role === 'admin_kota';
    }

    public function getIsAdminKecamatanAttribute(): bool
    {
        return $this->role === 'admin_kecamatan';
    }

    public function getIsAdminKelurahanAttribute(): bool
    {
        return $this->role === 'admin_kelurahan';
    }

    public function getIsNakesAttribute(): bool
    {
        return $this->role === 'nakes_puskesmas';
    }

    public function getIsKaderAttribute(): bool
    {
        return $this->role === 'kader';
    }

    public function getIsOrangtuaAttribute(): bool
    {
        return $this->role === 'orangtua';
    }
}
