<?php

namespace Database\Seeders;

use App\Facades\Setting;
use App\Models\Currency;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use LucasDotVin\Soulbscription\Models\Feature;

class FeatureAndPlanSeeder extends Seeder
{
    public function run(): void
    {
        // Önce feature'ı oluşturalım
        $feature = Feature::create([
            'name' => 'İçerik Yönetimi',
            'consumable' => true,
            'quota' => false,
            'postpaid' => false,
            'periodicity' => null,
            'periodicity_type' => null
        ]);

        // TRY currency'sini bulalım
        $currency = Currency::where('iso_code', Setting::get('currency'))->first();

        // Planları oluşturalım
        $plans = [
            [
                'base' => null,
                'name' => 'Ücretsiz',
                'description' => 'Ücretsiz Plan',
                'periodicity_type' => null,
                'periodicity' => null,
                'price' => 0,
                'currency_id' => $currency->id,
                'grace_days' => 0,
                'feature_limit' => 50
            ],
            [
                'base' => null,
                'name' => 'Standart',
                'description' => 'Standart Plan',
                'periodicity_type' => PeriodicityType::Month,
                'periodicity' => 1,
                'price' => 50,
                'currency_id' => $currency->id,
                'grace_days' => 0,
                'feature_limit' => 100
            ],
            [
                'base' => null,
                'name' => 'Profesyonel',
                'description' => 'Profesyonel Plan',
                'periodicity_type' => PeriodicityType::Month,
                'periodicity' => 1,
                'price' => 100,
                'currency_id' => $currency->id,
                'grace_days' => 7,
                'feature_limit' => 250
            ]
        ];

        foreach ($plans as $planData) {
            $featureLimit = $planData['feature_limit'];
            unset($planData['feature_limit']);

            $plan = Plan::create($planData);

            // Feature'ı plana bağlayalım ve limitini belirleyelim
            $plan->features()->attach($feature->id, ['charges' => $featureLimit]);
        }
    }
}
