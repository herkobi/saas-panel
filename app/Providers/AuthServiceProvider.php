<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Link;
use App\Models\Pixel;
use App\Models\Space;
use App\Policies\CampaignPolicy;
use App\Policies\LinkPolicy;
use App\Policies\PixelPolicy;
use App\Policies\SpacePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Panel erişim izinleri
        Gate::define('access-admin-panel', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('access-tenant-panel', function (User $user) {
            return $user->isTenantUser();
        });

        // Tenant yönetim izinleri
        Gate::define('manage-tenant-settings', function (User $user) {
            return $user->isTenantOwner();
        });

        Gate::define('manage-tenant-users', function (User $user) {
            return $user->isTenantOwner();
        });
    }
}
