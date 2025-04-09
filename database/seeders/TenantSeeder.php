<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    /**
     * Tenant ve kullanıcıları oluştur.
     */
    public function run(): void
    {
        // Önce tenant kaydı oluşturalım
        $tenant = Tenant::create([
            'name' => 'Demo Firma',
            'domain' => 'demo',
            'status' => true,
            'settings' => [
                'theme' => 'light',
                'language' => 'tr'
            ]
        ]);

        // Şimdi bu tenant'a sahip kullanıcı ekleyelim
        User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'type' => UserType::TENANT_OWNER,
            'tenant_id' => $tenant->id,
            'status' => true
        ]);

        // İsterseniz birkaç staff kullanıcısı da ekleyebiliriz
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@user.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'type' => UserType::TENANT_STAFF,
            'tenant_id' => $tenant->id,
            'status' => true
        ]);
    }
}
