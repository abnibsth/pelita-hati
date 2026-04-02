<?php

namespace App\Policies;

use App\Models\Kelurahan;
use App\Models\User;

class KelurahanPolicy
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
    public function view(User $user, Kelurahan $kelurahan): bool
    {
        if ($user->role === 'admin_kota') {
            return true;
        }

        if ($user->role === 'admin_kecamatan') {
            return $user->kecamatan_id === $kelurahan->kecamatan_id;
        }

        if ($user->role === 'admin_kelurahan') {
            return $user->kelurahan_id === $kelurahan->id;
        }

        if ($user->role === 'kader') {
            return $user->kelurahan_id === $kelurahan->id;
        }

        return false;
    }

    /**
     * Determine if the given user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin_kecamatan', 'admin_kota']);
    }

    /**
     * Determine if the given user can update the model.
     */
    public function update(User $user, Kelurahan $kelurahan): bool
    {
        if ($user->role === 'admin_kota') {
            return true;
        }

        if ($user->role === 'admin_kecamatan') {
            return $user->kecamatan_id === $kelurahan->kecamatan_id;
        }

        return false;
    }

    /**
     * Determine if the given user can delete the model.
     */
    public function delete(User $user, Kelurahan $kelurahan): bool
    {
        return in_array($user->role, ['admin_kecamatan', 'admin_kota']);
    }
}
