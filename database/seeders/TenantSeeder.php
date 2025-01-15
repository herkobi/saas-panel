<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Enums\AccountStatus;

class TenantSeeder extends Seeder
{
    public function run()
    {
        $tenants = [
            [
                'code' => Tenant::generateCode(),
                'domain' => 'demo',
                'has_domain' => false,
                'status' => AccountStatus::ACTIVE,
                'settings' => [
                    'locale' => 'tr'
                ]
            ]
        ];

        foreach ($tenants as $tenant) {
            Tenant::create($tenant);
        }
    }
}
