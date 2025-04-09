<?php

namespace App\Policies;

use App\Models\Content;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentPolicy
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
        // Tenant kullanıcıları content'leri görüntüleyebilir
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Content  $content
     * @return bool
     */
    public function view(User $user, Content $content): bool
    {
        // Sadece aynı tenant'a ait contentleri görüntüleyebilir
        return $user->tenant_id === $content->tenant_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Tüm tenant kullanıcıları content oluşturabilir
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Content  $content
     * @return bool
     */
    public function update(User $user, Content $content): bool
    {
        // Önce aynı tenant kontrolü
        if ($user->tenant_id !== $content->tenant_id) {
            return false;
        }

        // Tüm tenant kullanıcıları content güncelleyebilir
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Content  $content
     * @return bool
     */
    public function delete(User $user, Content $content): bool
    {
        // Önce aynı tenant kontrolü
        if ($user->tenant_id !== $content->tenant_id) {
            return false;
        }

        // Sadece tenant owner content silebilir
        return $user->isTenantOwner();
    }
}
