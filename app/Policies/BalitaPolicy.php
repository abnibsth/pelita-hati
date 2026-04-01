<?php

namespace App\Policies;

use App\Models\Balita;
use App\Models\User;

class BalitaPolicy
{
    /**
     * Determine if the given user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view list of balitas within their scope
        return true;
    }

    /**
     * Determine if the given user can view the model.
     */
    public function view(User $user, Balita $balita): bool
    {
        // Admin Kota can view all
        if ($user->role === 'admin_kota') {
            return true;
        }

        // Admin Kecamatan can view within their kecamatan
        if ($user->role === 'admin_kecamatan') {
            return $user->kecamatan_id === $balita->posyandu->kelurahan->kecamatan_id;
        }

        // Admin Kelurahan can view within their kelurahan
        if ($user->role === 'admin_kelurahan') {
            return $user->kelurahan_id === $balita->posyandu->kelurahan_id;
        }

        // Nakes Puskesmas can view within their puskesmas area
        if ($user->role === 'nakes_puskesmas') {
            return $user->puskesmas->kecamatan_id === $balita->posyandu->kelurahan->kecamatan_id;
        }

        // Kader can only view balitas in their posyandu
        if ($user->role === 'kader') {
            return $user->posyandu_id === $balita->posyandu_id;
        }

        // Orangtua can only view their own children
        if ($user->role === 'orangtua') {
            return $user->id === $balita->user_id;
        }

        return false;
    }

    /**
     * Determine if the given user can create models.
     */
    public function create(User $user): bool
    {
        // Only kader and admin kelurahan can create balitas
        return in_array($user->role, ['kader', 'admin_kelurahan']);
    }

    /**
     * Determine if the given user can update the model.
     */
    public function update(User $user, Balita $balita): bool
    {
        // Admin Kota can update all
        if ($user->role === 'admin_kota') {
            return true;
        }

        // Admin Kecamatan can update within their kecamatan
        if ($user->role === 'admin_kecamatan') {
            return $user->kecamatan_id === $balita->posyandu->kelurahan->kecamatan_id;
        }

        // Admin Kelurahan can update within their kelurahan
        if ($user->role === 'admin_kelurahan') {
            return $user->kelurahan_id === $balita->posyandu->kelurahan_id;
        }

        // Kader can only update balitas in their posyandu
        if ($user->role === 'kader') {
            return $user->posyandu_id === $balita->posyandu_id;
        }

        return false;
    }

    /**
     * Determine if the given user can delete the model.
     */
    public function delete(User $user, Balita $balita): bool
    {
        // Only admin kelurahan and admin kecamatan can delete
        return in_array($user->role, ['admin_kelurahan', 'admin_kecamatan', 'admin_kota']);
    }

    /**
     * Determine if the given user can restore the model.
     */
    public function restore(User $user, Balita $balita): bool
    {
        return false;
    }

    /**
     * Determine if the given user can permanently delete the model.
     */
    public function forceDelete(User $user, Balita $balita): bool
    {
        return false;
    }
}
