<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Enums\Status;
use App\Utils\Helper;
use App\Enums\UserType;
use App\Facades\Setting;
use App\Enums\AccountStatus;
use App\Enums\AgreementVersionStatus;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;

class AliasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Get the AliasLoader instance
        $loader = AliasLoader::getInstance();

        // Add your aliases
        $loader->alias('Status', Status::class);
        $loader->alias('AgreementVersionStatus', AgreementVersionStatus::class);
        $loader->alias('UserType', UserType::class);
        $loader->alias('AccountStatus', AccountStatus::class);
        $loader->alias('PeriodicityType', PeriodicityType::class);
        $loader->alias('Carbon', Carbon::class);
        $loader->alias('Setting', Setting::class);
        $loader->alias('Helper', Helper::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
