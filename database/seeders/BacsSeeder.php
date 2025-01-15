<?php

namespace Database\Seeders;

use App\Models\Bacs;
use App\Services\Admin\Tools\CurrencyService;
use Illuminate\Database\Seeder;
use App\Facades\Setting;

class BacsSeeder extends Seeder
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function run(): void
    {
        // Rastgele banka adları oluştur
        $bankNames = [
            'Global Bank',
            'National Trust',
            'City Savings',
            'United Credit',
            'Metro Finance',
        ];

        // Rastgele IBAN numaraları oluştur
        $ibans = [
            'TR330006100519786457841326',
            'DE89370400440532013000',
            'GB29NWBK60161331926819',
            'FR1420041010050500013M02606',
            'IT60X0542811101000000123456',
        ];

        // Currency ID'yi servis üzerinden al
        $currency = $this->currencyService->getCurrencyByCode(Setting::get('currency'));
        $currencyId = $currency->id;

        // 5 adet Bacs kaydı oluştur
        for ($i = 0; $i < 5; $i++) {
            Bacs::create([
                'currency_id' => $currencyId,
                'status' => 1, // ACTIVE
                'logo' => null, // Logo opsiyonel
                'bank_name' => $bankNames[$i],
                'account_holder' => 'John Doe', // Sabit hesap sahibi adı
                'account_number' => rand(100000000, 999999999), // Rastgele hesap numarası
                'iban' => $ibans[$i],
                'swift' => 'ABCD' . rand(1000, 9999), // Rastgele SWIFT kodu
            ]);
        }
    }
}
