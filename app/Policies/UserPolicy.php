<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the given user can view any models.
     */
    public function viewAny(User $authUser): bool
    {
        // Admin Kota and Admin Kecamatan can view users
        return in_array($authUser->role, ['admin_kota', 'admin_kecamatan', 'admin_kelurahan']);
    }

    /**
     * Determine if the given user can view the model.
     */
    public function view(User $authUser, User $user): bool
    {
        // Admin Kota can view all
        if ($authUser->role === 'admin_kota') {
            return true;
        }

        // Admin Kecamatan can view users within their kecamatan
        if ($authUser->role === 'admin_kecamatan') {
            if ($user->kecamatan_id) {
                return $authUser->kecamatan_id === $user->kecamatan_id;
            }
            if ($user->kelurahan_id) {
                return $authUser->kecamatan_id === $user->kelurahan->kecamatan_id;
            }
        }

        // Admin Kelurahan can view users within their kelurahan
        if ($authUser->role === 'admin_kelurahan') {
            return $authUser->kelurahan_id === $user->kelurahan_id;
        }

        // Users can view their own profile
        return $authUser->id === $user->id;
    }

    /**
     * Determine if the given user can create models.
     */
    public function create(User $authUser): bool
    {
        // Admin Kecamatan and Admin Kelurahan can create users
        return in_array($authUser->role, ['admin_kota', 'admin_kecamatan', 'admin_kelurahan']);
    }

    /**
     * Determine if the given user can update the model.
     */
    public function update(User $authUser, User $user): bool
    {
        // Admin Kota can update all
        if ($authUser->role === 'admin_kota') {
            return true;
        }

        // Admin Kecamatan can update users within their kecamatan
        if ($authUser->role === 'admin_kecamatan') {
            if ($user->kecamatan_id) {
                return $authUser->kecamatan_id === $user->kecamatan_id;
            }
            if ($user->kelurahan_id) {
                return $authUser->kecamatan_id === $user->kelurahan->kecamatan_id;
            }
        }

        // Admin Kelurahan can update users within their kelurahan
        if ($authUser->role === 'admin_kelurahan') {
            return $authUser->kelurahan_id === $user->kelurahan_id;
        }

        // Users can update their own profile
        return $authUser->id === $user->id;
    }

    /**
     * Determine if the given user can delete the model.
     */
    public function delete(User $authUser, User $user): bool
    {
        // Only admin kota and admin kecamatan can delete users
        return in_array($authUser->role, ['admin_kota', 'admin_kecamatan']);
    }
}
