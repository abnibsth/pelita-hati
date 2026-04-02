<?php

namespace App\Policies;

use App\Models\Kecamatan;
use App\Models\User;

class KecamatanPolicy
{
    /**
     * Determine if the given user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the given user can view the model.
     */
    public function view(User $user, Kecamatan $kecamatan): bool
    {
        if ($user->role === 'admin_kota') {
            return true;
        }

        if ($user->role === 'admin_kecamatan') {
            return $user->kecamatan_id === $kecamatan->id;
        }

        return false;
    }

    /**
     * Determine if the given user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin_kota';
    }

    /**
     * Determine if the given user can update the model.
     */
    public function update(User $user, Kecamatan $kecamatan): bool
    {
        if ($user->role === 'admin_kota') {
            return true;
        }

        if ($user->role === 'admin_kecamatan') {
            return $user->kecamatan_id === $kecamatan->id;
        }

        return false;
    }

    /**
     * Determine if the given user can delete the model.
     */
    public function delete(User $user, Kecamatan $kecamatan): bool
    {
        return $user->role === 'admin_kota';
    }
}
