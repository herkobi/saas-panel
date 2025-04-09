<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tenant Konfigürasyonu
    |--------------------------------------------------------------------------
    |
    | Bu dosya, çok kiracılı (multi-tenant) uygulamanız için gerekli
    | yapılandırma ayarlarını içerir.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Subdomain Desteği
    |--------------------------------------------------------------------------
    |
    | Subdomain desteği aktif olduğunda, her tenant kendi subdomaini
    | üzerinden erişilebilir olacaktır. (örn: tenant1.uygulamaniz.com)
    |
    */
    'subdomain_enabled' => env('TENANT_SUBDOMAIN_ENABLED', false),

     /*
    |--------------------------------------------------------------------------
    | Domain Ayarları
    |--------------------------------------------------------------------------
    |
    | Subdomain desteği aktif olduğunda kullanılacak domain ayarları.
    |
    */
    'domain' => [
        'main' => env('APP_URL', default: 'http://localhost'),
        'suffix' => env('TENANT_DOMAIN_SUFFIX', '.localhost'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Ülke Tanımlamaları
    |--------------------------------------------------------------------------
    |
    | Sistem içerisinde kullanılacak ülke tanımlamalarını içerir.
    | Her bir ülke için ad ve kod (ISO 3166-1 alpha-2) bilgileri tanımlanır.
    |
    */
    'countries' => [
        [
            'name' => 'Türkiye',
            'code' => 'TR'
        ],
        [
            'name' => 'Amerika Birleşik Devletleri',
            'code' => 'US'
        ],
        [
            'name' => 'Almanya',
            'code' => 'DE'
        ],
        [
            'name' => 'İngiltere',
            'code' => 'GB'
        ],
        [
            'name' => 'Fransa',
            'code' => 'FR'
        ],
        [
            'name' => 'İtalya',
            'code' => 'IT'
        ],
        [
            'name' => 'İspanya',
            'code' => 'ES'
        ],
        [
            'name' => 'Hollanda',
            'code' => 'NL'
        ],
        [
            'name' => 'Belçika',
            'code' => 'BE'
        ],
        [
            'name' => 'İsviçre',
            'code' => 'CH'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Para Birimi Tanımlamaları
    |--------------------------------------------------------------------------
    |
    | Sistem içerisinde kullanılacak para birimi tanımlamalarını içerir.
    | Her bir para birimi için:
    | - name: Para biriminin adı
    | - symbol: Para birimi sembolü
    | - position: Sembolün konumu (left, left_space, right, right_space)
    | - thousands_separator: Binlik ayracı
    | - decimal_separator: Ondalık ayracı
    | - decimals: Ondalık basamak sayısı
    | - iso_code: ISO 4217 standardına göre para birimi kodu
    |
    */
    'currencies' => [
        [
            'name' => 'Türk Lirası',
            'symbol' => '₺',
            'position' => 'left',
            'thousands_separator' => '.',
            'decimal_separator' => ',',
            'decimals' => 2,
            'iso_code' => 'TRY'
        ],
        [
            'name' => 'Amerikan Doları',
            'symbol' => '$',
            'position' => 'left',
            'thousands_separator' => ',',
            'decimal_separator' => '.',
            'decimals' => 2,
            'iso_code' => 'USD'
        ],
        [
            'name' => 'Euro',
            'symbol' => '€',
            'position' => 'right_space',
            'thousands_separator' => '.',
            'decimal_separator' => ',',
            'decimals' => 2,
            'iso_code' => 'EUR'
        ],
        [
            'name' => 'İngiliz Sterlini',
            'symbol' => '£',
            'position' => 'left',
            'thousands_separator' => ',',
            'decimal_separator' => '.',
            'decimals' => 2,
            'iso_code' => 'GBP'
        ],
        [
            'name' => 'İsviçre Frangı',
            'symbol' => 'CHF',
            'position' => 'right_space',
            'thousands_separator' => '\'',
            'decimal_separator' => '.',
            'decimals' => 2,
            'iso_code' => 'CHF'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Vergi Oranı Tanımlamaları
    |--------------------------------------------------------------------------
    |
    | Sistem içerisinde kullanılacak vergi oranı tanımlamalarını içerir.
    | Her bir vergi oranı için:
    | - name: Vergi adı
    | - code: Vergi kodu
    | - rate: Vergi oranı (% olarak)
    |
    */
    'tax_rates' => [
        [
            'name' => 'Katma Değer Vergisi',
            'code' => 'KDV',
            'rate' => 20
        ],
        [
            'name' => 'Özel Tüketim Vergisi',
            'code' => 'ÖTV',
            'rate' => 18
        ],
        [
            'name' => 'Düşük Oranlı KDV',
            'code' => 'KDV-1',
            'rate' => 10
        ],
        [
            'name' => 'İndirimli Oran',
            'code' => 'KDV-2',
            'rate' => 8
        ],
        [
            'name' => 'Vergisiz',
            'code' => 'KDV-0',
            'rate' => 0
        ],
    ],
];
