<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Country;
use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxesSeeder extends Seeder
{
    public function run()
    {
        $country = Country::where('code', 'TR')->first();
        $taxes = [
            [
                'title' => 'Katma Değer Vergisi',
                'code' => 'KDV',
                'value' => '20',
            ]
        ];

        foreach ($taxes as $taxData) {
            $tax = Tax::create([
                'status' => Status::ACTIVE,
                'title' => $taxData['title'],
                'code' => $taxData['code'],
                'value' => $taxData['value']
            ]);

            // Vergi bölgesi oluştur
            $tax->regions()->create([
                'country_id' => $country->id,
                'state_id' => null // Eğer spesifik bir state eklemek isterseniz burada belirtebilirsiniz
            ]);
        }
    }
}
