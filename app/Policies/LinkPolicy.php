<?php

namespace App\Policies;

use App\Models\Link;
use App\Models\User;
use App\Services\Tenant\FeatureAccessService;
use App\Services\Tenant\FeatureUsageService;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkPolicy
{
    use HandlesAuthorization;

    protected $featureName = 'link-yonetimi';

    public function __construct(protected FeatureAccessService $featureAccess, protected FeatureUsageService $featureUsage)
    {
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Tenant kullanıcıları linkleri görüntüleyebilir
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Link  $link
     * @return bool
     */
    public function view(User $user, Link $link): bool
    {
        // Sadece aynı tenant'a ait linkleri görüntüleyebilir
        return $user->tenant_id === $link->tenant_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Kullanıcı bir tenant kullanıcısı olmalı
        if (!$user->isTenantUser() || !$user->tenant) {
            return false;
        }

        // Kullanıcının tenant'ı bu özelliğe erişebilmeli
        if (!$this->featureAccess->canAccess($user->tenant, $this->featureName)) {
            return false;
        }

        // Kullanıcının kalan kullanım hakkı kontrolü
        $remainingUsage = $this->featureUsage->getRemainingUsage($user->tenant, $this->featureName);
        return $remainingUsage === -1 || $remainingUsage > 0; // -1: sınırsız
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Link  $link
     * @return bool
     */
    public function update(User $user, Link $link): bool
    {
        // Önce aynı tenant kontrolü
        if ($user->tenant_id !== $link->tenant_id) {
            return false;
        }

        // Özellik erişimi kontrolü
        if (!$this->featureAccess->canAccess($user->tenant, $this->featureName)) {
            return false;
        }

        // Tüm tenant kullanıcıları link güncelleyebilir
        return $user->isTenantUser();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Link  $link
     * @return bool
     */
    public function delete(User $user, Link $link): bool
    {
        // Önce aynı tenant kontrolü
        if ($user->tenant_id !== $link->tenant_id) {
            return false;
        }

        // Özellik erişimi kontrolü
        if (!$this->featureAccess->canAccess($user->tenant, $this->featureName)) {
            return false;
        }

        // Link silme işlemi için biraz daha kısıtlayıcı olabiliriz
        // Sadece tenant owner ve staff linklerini silebilir
        return $user->isTenantUser();
    }
}
