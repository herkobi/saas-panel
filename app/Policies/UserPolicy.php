<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tenant owner ve staff kendi tenant'ındaki kullanıcıları görebilir
        return $user->isTenantOwner() || $user->isTenantStaff();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Sadece aynı tenant içindeki kullanıcılar görülebilir
        return $user->tenant_id === $model->tenant_id &&
               ($user->isTenantOwner() || $user->isTenantStaff());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Sadece tenant owner yeni kullanıcı oluşturabilir
        return $user->isTenantOwner();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Owner kendi tenant'ındaki kullanıcıları güncelleyebilir
        // Kullanıcı kendi bilgilerini güncelleyebilir
        return ($user->isTenantOwner() && $user->tenant_id === $model->tenant_id) ||
               $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Sadece tenant owner, staff kullanıcılarını silebilir
        // Owner kendisini silemez
        return $user->isTenantOwner() &&
               $user->tenant_id === $model->tenant_id &&
               $model->isTenantStaff() &&
               $user->id !== $model->id;
    }

    /**
     * Determine whether the user can change status of the model.
     */
    public function changeStatus(User $user, User $model): bool
    {
        // Sadece tenant owner, staff kullanıcılarının durumunu değiştirebilir
        // Owner kendi durumunu değiştiremez
        return $user->isTenantOwner() &&
               $user->tenant_id === $model->tenant_id &&
               $model->isTenantStaff();
    }
}
