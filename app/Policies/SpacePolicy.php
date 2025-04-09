<?php

namespace App\Policies;

use App\Models\Space;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Tenant kullanıcıları space'leri görüntüleyebilir
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return bool
     */
    public function view(User $user, Space $space): bool
    {
        // Sadece aynı tenant'a ait spaceleri görüntüleyebilir
        return $user->tenant_id === $space->tenant_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Tüm tenant kullanıcıları space oluşturabilir
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return bool
     */
    public function update(User $user, Space $space): bool
    {
        // Önce aynı tenant kontrolü
        if ($user->tenant_id !== $space->tenant_id) {
            return false;
        }

        // Tüm tenant kullanıcıları space güncelleyebilir
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return bool
     */
    public function delete(User $user, Space $space): bool
    {
        // Önce aynı tenant kontrolü
        if ($user->tenant_id !== $space->tenant_id) {
            return false;
        }

        // Sadece tenant owner space silebilir
        return $user->isTenantOwner();
    }
}
