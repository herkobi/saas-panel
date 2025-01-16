<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAccount;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserMeta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingsTableSeeder::class,
            ContentTablesSeeder::class,
            CountriesSeeder::class,
            LanguagesSeeder::class,
            CurrenciesSeeder::class,
            AccountGroupSeeder::class,
            TaxesSeeder::class,
            OrderStatusSeeder::class,
            TenantSeeder::class,
            FeatureAndPlanSeeder::class,
            BacsSeeder::class,
        ]);

        // Demo tenant'ı al
        $tenant = \App\Models\Tenant::where('domain', 'demo')->first();

        //Süper Yönetici Hesabı ve Rolü
        $super = User::factory()->create();
        //Yönetici Hesabı ve Rolü
        $admin = User::factory()->adminUser()->create();
        //Kullanıcı Hesapları - tenant_id ekle
        $normal = User::factory()->normalUser()->create([
            'tenant_id' => $tenant->id,
            'is_tenant_owner' => true  // Tenant owner
        ]);
        $tenantUser = User::factory()->tenantUser()->create([
            'tenant_id' => $tenant->id,
            'is_tenant_owner' => false  // Normal tenant kullanıcısı
        ]);
        $draft = User::factory()->draftUser()->create(['tenant_id' => $tenant->id]);
        $passive = User::factory()->passiveUser()->create(['tenant_id' => $tenant->id]);
        $deleted = User::factory()->deletedUser()->create(['tenant_id' => $tenant->id]);
        $demo = User::factory()->demoUser()->create(['tenant_id' => $tenant->id]);

        /**
         * Kullanıcı klasörü oluşturuluyor.
         */
        $userTypes = ['super', 'admin', 'normal', 'tenantUser', 'draft', 'passive', 'deleted', 'demo'];
        foreach ($userTypes as $type) {
            $user = ${$type};
            $folderName = 'user_' . Str::random(12);

            if ($user->tenant_id && $tenant) {
                Storage::makeDirectory($tenant->getUserPath($folderName));
            } else {
                if (!Storage::exists('private/users/' . $folderName)) {
                    Storage::makeDirectory('private/users/' . $folderName);
                }
            }

            UserMeta::create([
                'user_id' => $user->id,
                'user_folder' => $folderName
            ]);
        }

        /**
         * Sadece tenant owner için fatura bilgileri oluştur
         */
        UserAccount::create([
            'user_id' => $normal->id,  // Sadece tenant owner
            'invoice_name' => $normal->name . ' ' . $normal->surname
        ]);
    }
}
