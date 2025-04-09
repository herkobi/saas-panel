<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
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
        // Aynı tenant'a ait tüm kullanıcılar kampanyaları görüntüleyebilir
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Campaign  $campaign
     * @return bool
     */
    public function view(User $user, Campaign $campaign): bool
    {
        // Sadece aynı tenant'a ait kampanyalar görüntülenebilir
        return $user->tenant_id === $campaign->tenant_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Tenant owner ve staff kullanıcıları kampanya oluşturabilir
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Campaign  $campaign
     * @return bool
     */
    public function update(User $user, Campaign $campaign): bool
    {
        // Sadece aynı tenant'a ait kampanyalar güncellenebilir
        if ($user->tenant_id !== $campaign->tenant_id) {
            return false;
        }

        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Campaign  $campaign
     * @return bool
     */
    public function delete(User $user, Campaign $campaign): bool
    {
        // Sadece aynı tenant'a ait kampanyalar silinebilir
        if ($user->tenant_id !== $campaign->tenant_id) {
            return false;
        }

        // Tenant owner ve staff kullanıcıları kampanya silebilir
        return $user->isTenantUser();
    }
}
