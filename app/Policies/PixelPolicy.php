<?php

namespace App\Policies;

use App\Models\Pixel;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Auth\Access\HandlesAuthorization;

class PixelPolicy
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
        // Tenant üyeleri pixel'leri görüntüleyebilir
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pixel  $pixel
     * @return bool
     */
    public function view(User $user, Pixel $pixel): bool
    {
        // Sadece aynı tenant'a ait pixelleri görüntüleyebilir
        return $user->tenant_id === $pixel->tenant_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Tenant üyeleri pixel oluşturabilir
        // Gerekirse sadece tenant owner izni verilebilir: return $user->isTenantOwner();
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pixel  $pixel
     * @return bool
     */
    public function update(User $user, Pixel $pixel): bool
    {
        // Önce aynı tenant kontrolü
        if ($user->tenant_id !== $pixel->tenant_id) {
            return false;
        }

        // Tüm tenant üyeleri pixelleri güncelleyebilir
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pixel  $pixel
     * @return bool
     */
    public function delete(User $user, Pixel $pixel): bool
    {
        // Önce aynı tenant kontrolü
        if ($user->tenant_id !== $pixel->tenant_id) {
            return false;
        }

        // Sadece tenant owner pixel silebilir (daha kısıtlayıcı)
        // Alternatif olarak: return $user->isTenantUser();
        return $user->isTenantOwner();
    }
}
