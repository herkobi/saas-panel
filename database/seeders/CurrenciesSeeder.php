<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Enums\Status;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    public function run()
    {
        $currencies = [
            [
                'name' => 'Türk Lirası',
                'symbol' => '₺',
                'symbol_position' => 'left', // left veya right
                'thousands_separator' => '.',
                'decimal_separator' => ',',
                'decimal_digits' => 2,
                'iso_code' => 'TRY'
            ],
            [
                'name' => 'Amerikan Doları',
                'symbol' => '$',
                'symbol_position' => 'left',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'USD'
            ],
            [
                'name' => 'Euro',
                'symbol' => '€',
                'symbol_position' => 'left',
                'thousands_separator' => '.',
                'decimal_separator' => ',',
                'decimal_digits' => 2,
                'iso_code' => 'EUR'
            ],
            [
                'name' => 'Japon Yeni',
                'symbol' => '¥',
                'symbol_position' => 'left',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 0,
                'iso_code' => 'JPY'
            ],
            [
                'name' => 'İngiliz Sterlini',
                'symbol' => '£',
                'symbol_position' => 'left',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'GBP'
            ],
            [
                'name' => 'İsviçre Frangı',
                'symbol' => 'CHF',
                'symbol_position' => 'left',
                'thousands_separator' => '\'',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'CHF'
            ],
            [
                'name' => 'Çin Yuanı',
                'symbol' => '¥',
                'symbol_position' => 'left',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'CNY'
            ],
            [
                'name' => 'Avustralya Doları',
                'symbol' => 'A$',
                'symbol_position' => 'left',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'AUD'
            ],
            [
                'name' => 'Kanada Doları',
                'symbol' => 'C$',
                'symbol_position' => 'left',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'CAD'
            ],
            [
                'name' => 'Hong Kong Doları',
                'symbol' => 'HK$',
                'symbol_position' => 'left',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'HKD'
            ],
            [
                'name' => 'Güney Kore Wonu',
                'symbol' => '₩',
                'symbol_position' => 'left',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 0,
                'iso_code' => 'KRW'
            ],
            [
                'name' => 'Hindistan Rupisi',
                'symbol' => '₹',
                'symbol_position' => 'left',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'INR'
            ],
            [
                'name' => 'Brezilya Reali',
                'symbol' => 'R$',
                'symbol_position' => 'left',
                'thousands_separator' => '.',
                'decimal_separator' => ',',
                'decimal_digits' => 2,
                'iso_code' => 'BRL'
            ],
            [
                'name' => 'Rus Rublesi',
                'symbol' => '₽',
                'symbol_position' => 'right',
                'thousands_separator' => ' ',
                'decimal_separator' => ',',
                'decimal_digits' => 2,
                'iso_code' => 'RUB'
            ],
            [
                'name' => 'Singapur Doları',
                'symbol' => 'S$',
                'symbol_position' => 'left',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'SGD'
            ],
            [
                'name' => 'BAE Dirhemi',
                'symbol' => 'د.إ',
                'symbol_position' => 'right',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'AED'
            ],
            [
                'name' => 'Suudi Arabistan Riyali',
                'symbol' => 'ر.س',
                'symbol_position' => 'right',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'SAR'
            ],
            [
                'name' => 'İsveç Kronu',
                'symbol' => 'kr',
                'symbol_position' => 'right',
                'thousands_separator' => ' ',
                'decimal_separator' => ',',
                'decimal_digits' => 2,
                'iso_code' => 'SEK'
            ],
            [
                'name' => 'Yeni Zelanda Doları',
                'symbol' => 'NZ$',
                'symbol_position' => 'left',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'NZD'
            ],
            [
                'name' => 'Meksika Pesosu',
                'symbol' => '$',
                'symbol_position' => 'left',
                'thousands_separator' => ',',
                'decimal_separator' => '.',
                'decimal_digits' => 2,
                'iso_code' => 'MXN'
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::create([
                'status' => $currency['iso_code'] === 'TRY' ? Status::ACTIVE : Status::PASSIVE,
                'name' => $currency['name'],
                'symbol' => $currency['symbol'],
                'symbol_position' => $currency['symbol_position'],
                'thousands_separator' => $currency['thousands_separator'],
                'decimal_separator' => $currency['decimal_separator'],
                'decimal_digits' => $currency['decimal_digits'],
                'iso_code' => $currency['iso_code']
            ]);
        }
    }
}
