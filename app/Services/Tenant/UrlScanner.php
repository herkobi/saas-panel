<?php

namespace App\Services\Tenant;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UrlScanner
{
    /**
     * URL'in güvenli olup olmadığını kontrol eder
     *
     * @param string $url Kontrol edilecek URL
     * @return array Sonuç bilgisi ['is_safe' => boolean, 'message' => string, 'threats' => array]
     */
    public function scanUrl(string $url): array
    {
        // Cache'de bu URL için sonuç var mı kontrol et
        $cacheKey = 'url_scan_' . md5($url);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Temel URL güvenlik kontrollerini yap
        $basicCheckResult = $this->performBasicChecks($url);
        if (!$basicCheckResult['is_safe']) {
            return $this->cacheAndReturnResult($cacheKey, $basicCheckResult);
        }

        // Google Safe Browsing API entegrasyonu etkinse kontrol et
        if (config('link-safety.use_safe_browsing_api', false)) {
            $apiCheckResult = $this->checkWithSafeBrowsingApi($url);
            if (!$apiCheckResult['is_safe']) {
                return $this->cacheAndReturnResult($cacheKey, $apiCheckResult);
            }
        }

        // Tüm kontrollerden geçerse URL güvenlidir
        $result = [
            'is_safe' => true,
            'message' => 'URL güvenlidir.',
            'threats' => []
        ];

        return $this->cacheAndReturnResult($cacheKey, $result);
    }

    /**
     * Sonucu cache'e kaydeder ve döndürür
     */
    private function cacheAndReturnResult(string $cacheKey, array $result): array
    {
        // Sonucu 6 saat cache'de tut
        Cache::put($cacheKey, $result, 60 * 60 * 6);
        return $result;
    }

    /**
     * Temel URL kontrollerini gerçekleştirir
     */
    private function performBasicChecks(string $url): array
    {
        $result = [
            'is_safe' => true,
            'message' => 'URL güvenlidir.',
            'threats' => []
        ];

        // URL parse işlemi
        $parsedUrl = parse_url($url);

        // URL yapısı kontrolü
        if (!isset($parsedUrl['host']) || empty($parsedUrl['host'])) {
            $result['is_safe'] = false;
            $result['message'] = 'Geçersiz URL formatı.';
            $result['threats'][] = 'invalid_format';
            return $result;
        }

        $host = $parsedUrl['host'];

        // Tehlikeli protokol kontrolü
        if (isset($parsedUrl['scheme'])) {
            $dangerousSchemes = ['javascript', 'data', 'vbscript', 'file'];
            if (in_array(strtolower($parsedUrl['scheme']), $dangerousSchemes)) {
                $result['is_safe'] = false;
                $result['message'] = 'Güvensiz protokol kullanımı.';
                $result['threats'][] = 'dangerous_protocol';
                return $result;
            }
        }

        // Blacklist kontrolü
        $blacklist = $this->getBlacklist();
        foreach ($blacklist as $pattern) {
            if (Str::is($pattern, $host) || stripos($host, $pattern) !== false) {
                $result['is_safe'] = false;
                $result['message'] = 'Domain blacklist\'te.';
                $result['threats'][] = 'blacklisted_domain';
                return $result;
            }
        }

        // Whitelist kontrolü (eğer whitelist etkinse)
        if (config('link-safety.use_whitelist', false)) {
            $whitelist = $this->getWhitelist();
            if (!empty($whitelist)) {
                $domainAllowed = false;
                foreach ($whitelist as $pattern) {
                    if (Str::is($pattern, $host) || stripos($host, $pattern) !== false) {
                        $domainAllowed = true;
                        break;
                    }
                }

                if (!$domainAllowed) {
                    $result['is_safe'] = false;
                    $result['message'] = 'Domain whitelist\'te değil.';
                    $result['threats'][] = 'not_whitelisted';
                    return $result;
                }
            }
        }

        // URL içeriğinde tehlikeli kelime kontrolü
        $keywordBlacklist = config('link-safety.keyword_blacklist', []);
        foreach ($keywordBlacklist as $keyword) {
            if (stripos($url, $keyword) !== false) {
                $result['is_safe'] = false;
                $result['message'] = 'URL içeriğinde şüpheli kelime.';
                $result['threats'][] = 'suspicious_keyword';
                return $result;
            }
        }

        return $result;
    }

    /**
     * Google Safe Browsing API ile URL kontrolü
     */
    private function checkWithSafeBrowsingApi(string $url): array
    {
        $result = [
            'is_safe' => true,
            'message' => 'URL güvenlidir.',
            'threats' => []
        ];

        try {
            $apiKey = config('link-safety.safe_browsing_api_key');
            if (empty($apiKey)) {
                // API anahtarı yoksa kontrol yapma
                return $result;
            }

            // Google Safe Browsing API URL'i
            $apiUrl = 'https://safebrowsing.googleapis.com/v4/threatMatches:find';

            // API isteği için veri hazırla
            $requestBody = [
                'client' => [
                    'clientId' => config('app.name', 'Laravel'),
                    'clientVersion' => '1.0.0',
                ],
                'threatInfo' => [
                    'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'],
                    'platformTypes' => ['ANY_PLATFORM'],
                    'threatEntryTypes' => ['URL'],
                    'threatEntries' => [
                        ['url' => $url]
                    ]
                ]
            ];

            // API'ye istek gönder
            $response = Http::post($apiUrl . '?key=' . $apiKey, $requestBody);

            if ($response->successful()) {
                $data = $response->json();

                // Tehdit bulundu mu kontrol et
                if (isset($data['matches']) && !empty($data['matches'])) {
                    $result['is_safe'] = false;
                    $result['message'] = 'URL Google Safe Browsing API tarafından tehlikeli olarak işaretlenmiş.';

                    // Tespit edilen tehditleri ekle
                    foreach ($data['matches'] as $match) {
                        $result['threats'][] = $match['threatType'] ?? 'unknown_threat';
                    }
                }
            } else {
                // API hatası durumunda log tut ama URL'i güvenli say
                Log::warning('Google Safe Browsing API hatası: ' . $response->status() . ' - ' . $response->body());
            }
        } catch (\Exception $e) {
            // Hata durumunda log tut ama URL'i güvenli say
            Log::error('Google Safe Browsing API exception: ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * Blacklist'i getir
     */
    private function getBlacklist(): array
    {
        // Önce cache'ten kontrol et
        if (Cache::has('url_blacklist')) {
            return Cache::get('url_blacklist');
        }

        // Config dosyasından al
        $blacklist = config('link-safety.blacklist', []);

        // Cache'e kaydet (5 dakika)
        Cache::put('url_blacklist', $blacklist, 300);

        return $blacklist;
    }

    /**
     * Whitelist'i getir
     */
    private function getWhitelist(): array
    {
        // Önce cache'ten kontrol et
        if (Cache::has('url_whitelist')) {
            return Cache::get('url_whitelist');
        }

        // Config dosyasından al
        $whitelist = config('link-safety.whitelist', []);

        // Cache'e kaydet (5 dakika)
        Cache::put('url_whitelist', $whitelist, 300);

        return $whitelist;
    }
}
