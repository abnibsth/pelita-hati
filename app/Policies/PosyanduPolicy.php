<?php

namespace App\Policies;

use App\Models\Posyandu;
use App\Models\User;

class PosyanduPolicy
{
    /**
     * Determine if the given user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view posyandus within their scope
        return true;
    }

    /**
     * Determine if the given user can view the model.
     */
    public function view(User $user, Posyandu $posyandu): bool
    {
        // Admin Kota can view all
        if ($user->role === 'admin_kota') {
            return true;
        }

        // Load kelurahan with kecamatan if not already loaded
        if (!$posyandu->relationLoaded('kelurahan')) {
            $posyandu->load('kelurahan.kecamatan');
        }

        // Admin Kecamatan can view within their kecamatan
        if ($user->role === 'admin_kecamatan') {
            return $user->kecamatan_id === $posyandu->kelurahan?->kecamatan_id;
        }

        // Admin Kelurahan can view within their kelurahan
        if ($user->role === 'admin_kelurahan') {
            return $user->kelurahan_id === $posyandu->kelurahan_id;
        }

        // Kader can view their own posyandu
        if ($user->role === 'kader') {
            return $user->posyandu_id === $posyandu->id;
        }

        return false;
    }

    /**
     * Determine if the given user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin kelurahan and admin kecamatan can create posyandus
        return in_array($user->role, ['admin_kelurahan', 'admin_kecamatan', 'admin_kota']);
    }

    /**
     * Determine if the given user can update the model.
     */
    public function update(User $user, Posyandu $posyandu): bool
    {
        // Admin Kota can update all
        if ($user->role === 'admin_kota') {
            return true;
        }

        // Admin Kecamatan can update within their kecamatan
        if ($user->role === 'admin_kecamatan') {
            return $user->kecamatan_id === $posyandu->kelurahan->kecamatan_id;
        }

        // Admin Kelurahan can update within their kelurahan
        return $user->role === 'admin_kelurahan' && $user->kelurahan_id === $posyandu->kelurahan_id;
    }

    /**
     * Determine if the given user can delete the model.
     */
    public function delete(User $user, Posyandu $posyandu): bool
    {
        // Only admin kelurahan and admin kecamatan can delete
        return in_array($user->role, ['admin_kelurahan', 'admin_kecamatan', 'admin_kota']);
    }
}
