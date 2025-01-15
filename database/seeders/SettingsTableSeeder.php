<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'title',
                'value' => 'Herkobi Panel'
            ],
            [
                'key' => 'slogan',
                'value' => 'Herkobi SaaS Panel'
            ],
            [
                'key' => 'logo',
                'value' => 'herkobi.png'
            ],
            [
                'key' => 'favicon',
                'value' => 'favicon.png'
            ],
            [
                'key' => 'email',
                'value' => 'contact@example.com'
            ],
            [
                'key' => 'language',
                'value' => 'tr'
            ],
            [
                'key' => 'location',
                'value' => 'TR'
            ],
            [
                'key' => 'currency',
                'value' => 'TRY'
            ],
            [
                'key' => 'tax',
                'value' => 'KDV'
            ],
            [
                'key' => 'timezone',
                'value' => 'Europe/Istanbul'
            ],
            [
                'key' => 'dateformat',
                'value' => 'm/d/Y'
            ],
            [
                'key' => 'timeformat',
                'value' => 'H:i'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
