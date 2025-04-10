<?php

namespace Database\Seeders;

use App\Enums\ContractType;
use App\Models\Contract;
use App\Models\Feature;
use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Features oluştur
        $this->createFeatures();

        // Plans oluştur
        $this->createPlans();

        // Plan ve Feature ilişkilerini kur
        $this->attachFeaturesToPlans();

        // Sözleşme oluştur
        $this->createContracts();
    }

    /**
     * Özellikleri oluştur
     */
    private function createFeatures(): void
    {
        $features = [
            [
                'name' => 'Link Yönetimi',
                'slug' => 'link-yonetimi',
                'description' => 'Link kısaltma ve yönlendirme özellikleriyle link yönetimi',
                'status' => true,
            ],
            [
                'name' => 'Kampanya Yönetimi',
                'slug' => 'kampanya-yonetimi',
                'description' => 'Kampanyalarınızı oluşturup yönetebilmenizi sağlar',
                'status' => true,
            ],
            [
                'name' => 'Başvuru Yönetimi',
                'slug' => 'basvuru-yonetimi',
                'description' => 'Başvuru formları oluşturup yönetebilmenizi sağlar',
                'status' => true,
            ],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(
                ['slug' => $feature['slug']],
                $feature
            );
        }
    }

    /**
     * Planları oluştur
     */
    private function createPlans(): void
    {
        $plans = [
            [
                'name' => 'Link Paketi',
                'description' => 'Temel link yönetimi için uygun bir paket',
                'is_featured' => false,
                'is_free' => false,
                'billing_period' => 'yearly',
                'country_code' => 'TR',
                'currency_code' => 'TRY',
                'tax_rate_code' => 'kdv20',
                'monthly_price' => null,
                'yearly_price' => 1000.00,
                'trial_days' => 0,
                'grace_period_days' => 3,
                'payment_timing' => 'upfront',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Kampanya Paketi',
                'description' => 'Link ve kampanya yönetimi için gelişmiş paket',
                'is_featured' => true,
                'is_free' => false,
                'billing_period' => 'yearly',
                'country_code' => 'TR',
                'currency_code' => 'TRY',
                'tax_rate_code' => 'kdv20',
                'monthly_price' => null,
                'yearly_price' => 1500.00,
                'trial_days' => 0,
                'grace_period_days' => 3,
                'payment_timing' => 'upfront',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Başvuru Paketi',
                'description' => 'Tüm özellikleri içeren en kapsamlı paket',
                'is_featured' => false,
                'is_free' => false,
                'billing_period' => 'yearly',
                'country_code' => 'TR',
                'currency_code' => 'TRY',
                'tax_rate_code' => 'kdv20',
                'monthly_price' => null,
                'yearly_price' => 2500.00,
                'trial_days' => 0,
                'grace_period_days' => 3,
                'payment_timing' => 'upfront',
                'status' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }

    /**
     * Plan ve Feature ilişkilerini kur
     */
    private function attachFeaturesToPlans(): void
    {
        // Özellikleri getir
        $linkYonetimi = Feature::where('slug', 'link-yonetimi')->first();
        $kampanyaYonetimi = Feature::where('slug', 'kampanya-yonetimi')->first();
        $basvuruYonetimi = Feature::where('slug', 'basvuru-yonetimi')->first();

        // Planları getir
        $linkPaketi = Plan::where('name', 'Link Paketi')->first();
        $kampanyaPaketi = Plan::where('name', 'Kampanya Paketi')->first();
        $basvuruPaketi = Plan::where('name', 'Başvuru Paketi')->first();

        // Mevcut planFeatures'ları temizle
        PlanFeature::whereIn('plan_id', [$linkPaketi->id, $kampanyaPaketi->id, $basvuruPaketi->id])->delete();

        // Link Paketi Özellikleri
        PlanFeature::create([
            'plan_id' => $linkPaketi->id,
            'feature_id' => $linkYonetimi->id,
            'access_type' => 'limited',
            'limit_type' => 'cumulative',
            'limit_value' => 1000,
            'limit_reset_period' => null,
            'restore_on_delete' => false,
        ]);

        // Kampanya Paketi Özellikleri
        PlanFeature::create([
            'plan_id' => $kampanyaPaketi->id,
            'feature_id' => $linkYonetimi->id,
            'access_type' => 'limited',
            'limit_type' => 'cumulative',
            'limit_value' => -1, // Sınırsız
            'limit_reset_period' => null,
            'restore_on_delete' => false,
        ]);

        PlanFeature::create([
            'plan_id' => $kampanyaPaketi->id,
            'feature_id' => $kampanyaYonetimi->id,
            'access_type' => 'limited',
            'limit_type' => 'renewable',
            'limit_value' => 100,
            'limit_reset_period' => 'monthly',
            'restore_on_delete' => false,
        ]);

        // Başvuru Paketi Özellikleri
        PlanFeature::create([
            'plan_id' => $basvuruPaketi->id,
            'feature_id' => $linkYonetimi->id,
            'access_type' => 'limited',
            'limit_type' => 'cumulative',
            'limit_value' => -1, // Sınırsız
            'limit_reset_period' => null,
            'restore_on_delete' => false,
        ]);

        PlanFeature::create([
            'plan_id' => $basvuruPaketi->id,
            'feature_id' => $kampanyaYonetimi->id,
            'access_type' => 'limited',
            'limit_type' => 'cumulative',
            'limit_value' => -1, // Sınırsız
            'limit_reset_period' => null,
            'restore_on_delete' => false,
        ]);

        PlanFeature::create([
            'plan_id' => $basvuruPaketi->id,
            'feature_id' => $basvuruYonetimi->id,
            'access_type' => 'limited',
            'limit_type' => 'cumulative',
            'limit_value' => 100,
            'limit_reset_period' => null,
            'restore_on_delete' => false,
        ]);
    }

    /**
     * Sözleşmeleri oluştur
     */
    private function createContracts(): void
    {
        Contract::updateOrCreate(
            ['slug' => 'mesafeli-satis-sozlesmesi'],
            [
                'title' => 'Mesafeli Satış Sözleşmesi',
                'slug' => 'mesafeli-satis-sozlesmesi',
                'content' => '<h1>Mesafeli Satış Sözleşmesi</h1><p>Bu sözleşme, hizmet alımına ilişkin genel şartları içerir...</p>',
                'type' => ContractType::PAYMENT->value,
                'date' => now(),
                'status' => true,
            ]
        );
    }
}
