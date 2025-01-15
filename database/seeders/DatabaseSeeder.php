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
        $normal = User::factory()->normalUser()->create(['tenant_id' => $tenant->id]);
        $draft = User::factory()->draftUser()->create(['tenant_id' => $tenant->id]);
        $passive = User::factory()->passiveUser()->create(['tenant_id' => $tenant->id]);
        $deleted = User::factory()->deletedUser()->create(['tenant_id' => $tenant->id]);
        $demo = User::factory()->demoUser()->create(['tenant_id' => $tenant->id]);

        /**
         * Kullanıcı klasörü oluşturuluyor.
         */
        $userTypes = ['super', 'admin', 'normal', 'draft', 'passive', 'deleted', 'demo'];
        foreach ($userTypes as $type) {
            $user = ${$type};
            $folderName = 'user_' . Str::random(12);

            if ($user->tenant_id && $tenant) {
                // Tenant'a ait kullanıcılar için private/tenants altında klasör oluştur
                Storage::makeDirectory($tenant->getUserPath($folderName));
            } else {
                // Yöneticiler için private altında klasör oluştur
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
         * Kullanıcı fatura bilgileri oluşturuluyor.
         */
        $userAccounts = ['normal', 'draft', 'passive', 'deleted', 'demo'];
        foreach ($userAccounts as $type) {
            $user = ${$type};
            UserAccount::create([
                'user_id' => $user->id,
                'invoice_name' => $user->name . ' ' . $user->surname
            ]);
        }
    }
}
