<?php

return [

    /*
    |--------------------------------------------------------------------------
    | URL Güvenlik Ayarları
    |--------------------------------------------------------------------------
    |
    | Bu dosya, uygulamadaki URL'lerin güvenlik kontrollerini yapılandırmak için kullanılır.
    | Whitelist, blacklist ve diğer güvenlik ayarlarını buradan yönetebilirsiniz.
    |
    */

    // Whitelist'in aktif olup olmadığı
    'use_whitelist' => env('USE_URL_WHITELIST', false),

    // IP adreslerine doğrudan yönlendirme izni
    'allow_ip_urls' => env('ALLOW_IP_URLS', false),

    // Google Safe Browsing API'nin etkin olup olmadığı
    'use_safe_browsing_api' => env('USE_SAFE_BROWSING_API', false),

    // Google Safe Browsing API anahtarı
    'safe_browsing_api_key' => env('SAFE_BROWSING_API_KEY', ''),

    // Güvenlik kontrolünden geçemeyen URL'lerin oluşturulmasına izin ver
    'allow_unsafe_urls' => env('ALLOW_UNSAFE_URLS', false),

    // Güvenlik kontrolünden geçemeyen pixellerin oluşturulmasına izin ver
    'allow_unsafe_pixels' => env('ALLOW_UNSAFE_PIXELS', false),

    // Blacklist - bu domainlere yönlendirme yapılamaz
    'blacklist' => [
        // Örnek engelli domainler
        'evil.example.com',
        'malware.example.net',
        'phishing.example.org',
        // Wildcard ile domain engellenebilir
        '*.evil-domain.com',
    ],

    // Whitelist - eğer whitelist aktifse, sadece bu domainlere yönlendirme yapılabilir
    'whitelist' => [
        // Örnek izin verilen domainler
        'google.com',
        'github.com',
        'anthropic.com',
        // Wildcard ile toplu izin verilebilir
        '*.safe-domain.com',
    ],

    // Anahtar kelime bazlı engelleme listesi
    'keyword_blacklist' => [
        'malware',
        'trojan',
        'phishing',
        'scam',
    ],
];
