<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Content;
use App\Policies\UserPolicy;
use App\Policies\ContentPolicy;
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
        Content::class => ContentPolicy::class,
        User::class => UserPolicy::class
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

        // Content yönetim izinleri
        Gate::define('manage-content', function (User $user) {
            return $user->isTenantUser();
        });
    }
}
