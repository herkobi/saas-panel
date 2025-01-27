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
        // Currency'i bul
        $currency = Currency::where('iso_code', Setting::get('currency'))->first();

        // Features
        $features = [
            [
                'name' => 'icerik-yonetimi',
                'consumable' => true,
                'quota' => false,
                'postpaid' => false,
                'periodicity' => null,
                'periodicity_type' => null
            ]
        ];

        // Feature'ları oluştur
        $createdFeatures = [];
        foreach ($features as $feature) {
            $createdFeatures[$feature['name']] = Feature::create($feature);
        }

        // Plans
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
                'features' => [
                    'icerik-yonetimi' => 10
                ]
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
                'features' => [
                    'icerik-yonetimi' => 50,
                ]
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
                'features' => [
                    'icerik-yonetimi' => 100,
                ]
            ]
        ];

        // Planları oluştur ve feature'ları ekle
        foreach ($plans as $planData) {
            $features = $planData['features'];
            unset($planData['features']);

            $plan = Plan::create($planData);

            // Feature'ları plana bağla
            foreach ($features as $featureName => $limit) {
                $plan->features()->attach($createdFeatures[$featureName]->id, ['charges' => $limit]);
            }
        }
    }
}
