<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class ValidateUrlSafetyRule implements Rule
{
    /**
     * The error message
     *
     * @var string
     */
    private $message = 'Girilen URL güvenlik kontrolünden geçemedi.';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Boş URL kontrolü
        if (empty($value)) {
            return true;
        }

        // URL parse işlemi
        $parsedUrl = parse_url($value);

        // URL yapısı kontrolü
        if (!isset($parsedUrl['host']) || empty($parsedUrl['host'])) {
            $this->message = 'Geçerli bir URL formatı giriniz.';
            return false;
        }

        // Tehlikeli protokol kontrolü
        if (isset($parsedUrl['scheme'])) {
            $dangerousSchemes = ['javascript', 'data', 'vbscript', 'file'];
            if (in_array(strtolower($parsedUrl['scheme']), $dangerousSchemes)) {
                $this->message = 'Güvenlik nedeniyle bu protokol kabul edilmez.';
                return false;
            }
        }

        // HTTP veya HTTPS protokolü kontrolü
        if (!isset($parsedUrl['scheme']) || !in_array(strtolower($parsedUrl['scheme']), ['http', 'https'])) {
            $this->message = 'URL, HTTP veya HTTPS protokolü ile başlamalıdır.';
            return false;
        }

        // IP adresi kontrolü (doğrudan IP adreslerine izin vermek tehlikeli olabilir)
        $host = $parsedUrl['host'];
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            // Config'den IP adreslerine izin veriliyor mu kontrol et
            if (!config('link-safety.allow_ip_urls', false)) {
                $this->message = 'Doğrudan IP adreslerine yönlendirme güvenlik nedeniyle engellenmiştir.';
                return false;
            }
        }

        // Blacklist kontrolü
        $blacklist = $this->getBlacklist();
        foreach ($blacklist as $pattern) {
            if (Str::is($pattern, $host) || stripos($host, $pattern) !== false) {
                $this->message = 'Bu domain güvenlik nedeniyle engellenmiştir.';
                return false;
            }
        }

        // Whitelist kontrolü (eğer whitelist etkinse ve domain listede değilse engelle)
        $whitelist = $this->getWhitelist();
        if (!empty($whitelist) && config('link-safety.use_whitelist', false)) {
            $domainAllowed = false;
            foreach ($whitelist as $pattern) {
                if (Str::is($pattern, $host) || stripos($host, $pattern) !== false) {
                    $domainAllowed = true;
                    break;
                }
            }

            if (!$domainAllowed) {
                $this->message = 'Yalnızca izin verilen domainlere yönlendirme yapılabilir.';
                return false;
            }
        }

        // URL içerisinde tehlikeli parametre kontrolü
        if (isset($parsedUrl['query'])) {
            $query = $parsedUrl['query'];

            // JavaScript enjeksiyon denemeleri
            $dangerousPatterns = [
                'javascript:', 'document.cookie', 'eval(', 'onload=', 'onclick=',
                'onerror=', '<script', 'alert(', 'String.fromCharCode', 'fromCharCode',
                'document.write', 'window.location'
            ];

            foreach ($dangerousPatterns as $pattern) {
                if (stripos($query, $pattern) !== false) {
                    $this->message = 'URL içerisinde potansiyel olarak tehlikeli parametreler tespit edildi.';
                    return false;
                }
            }
        }

        // Eğer tüm kontrollerden geçerse URL güvenlidir
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * Get blacklisted domains
     *
     * @return array
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
     * Get whitelisted domains
     *
     * @return array
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
