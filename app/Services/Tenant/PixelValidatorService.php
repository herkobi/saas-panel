<?php

namespace App\Services\Tenant;

use App\Enums\PixelType;
use App\Models\Pixel;
use Illuminate\Support\Facades\Log;

class PixelValidatorService
{
    /**
     * Tehlikeli JavaScript pattern'lerini kontrol eder
     *
     * @param string $pixelCode Kontrol edilecek pixel kodu
     * @return array Sonuç bilgisi ['is_safe' => boolean, 'issues' => array]
     */
    public function validatePixelCode(string $pixelCode): array
    {
        $result = [
            'is_safe' => true,
            'issues' => []
        ];

        // Potansiyel tehlikeli JavaScript kodlarını tespit et
        $dangerousPatterns = $this->getDangerousPatterns();

        foreach ($dangerousPatterns as $pattern => $description) {
            if (preg_match($pattern, $pixelCode)) {
                $result['is_safe'] = false;
                $result['issues'][] = $description;
            }
        }

        return $result;
    }

    /**
     * Pixel tipine göre güvenlik kontrolü yapar
     *
     * @param Pixel $pixel Kontrol edilecek pixel
     * @return array Sonuç bilgisi ['is_safe' => boolean, 'issues' => array]
     */
    public function validatePixel(Pixel $pixel): array
    {
        // Pixel içeriğini kontrol et
        $codeValidation = $this->validatePixelCode($pixel->value);

        // Pixel tipi özel kontrollerini yap
        $typeValidation = $this->validatePixelType($pixel->type, $pixel->value);

        // Sonuçları birleştir
        $result = [
            'is_safe' => $codeValidation['is_safe'] && $typeValidation['is_safe'],
            'issues' => array_merge($codeValidation['issues'], $typeValidation['issues'])
        ];

        // Sonuçları loglama
        if (!$result['is_safe']) {
            Log::warning('Güvenli olmayan pixel tespit edildi', [
                'pixel_id' => $pixel->id,
                'pixel_name' => $pixel->name,
                'pixel_type' => $pixel->type,
                'issues' => $result['issues']
            ]);
        }

        return $result;
    }

    /**
     * Pixel tipine göre kontrol yapar
     *
     * @param PixelType $pixelType
     * @param string $pixelValue
     * @return array
     */
    private function validatePixelType(PixelType $pixelType, string $pixelValue): array
    {
        $result = [
            'is_safe' => true,
            'issues' => []
        ];

        switch ($pixelType) {
            case PixelType::FACEBOOK:
                // Facebook pixel ID formatı kontrol et
                if (!preg_match('/^\d{15,}$/', $this->extractFacebookPixelId($pixelValue))) {
                    $result['is_safe'] = false;
                    $result['issues'][] = 'Facebook Pixel ID formatı geçersiz.';
                }
                break;

            case PixelType::GOOGLE_ADS:
            case PixelType::GOOGLE_ANALYTICS:
            case PixelType::GOOGLE_TAG_MANAGER:
                // Google tag formatı kontrol et
                if (!preg_match('/^(UA|AW|G|GTM)-[a-zA-Z0-9\-]+$/', $this->extractGoogleId($pixelValue))) {
                    $result['is_safe'] = false;
                    $result['issues'][] = 'Google tag ID formatı geçersiz.';
                }
                break;

            // Diğer pixel tipleri için özel kontrollerı ekleyebilirsiniz
        }

        return $result;
    }

    /**
     * Tehlikeli JavaScript pattern'lerini döndürür
     *
     * @return array
     */
    private function getDangerousPatterns(): array
    {
        return [
            '/eval\s*\(/i' => 'Tehlikeli eval() fonksiyonu kullanımı tespit edildi.',
            '/document\.cookie/i' => 'Cookie erişimi tespit edildi.',
            '/localStorage/i' => 'localStorage erişimi tespit edildi.',
            '/sessionStorage/i' => 'sessionStorage erişimi tespit edildi.',
            '/\bwindow\.open\b/i' => 'window.open() kullanımı tespit edildi.',
            '/\blocation\b.*=.*[\'"]http/i' => 'Sayfa yönlendirmesi tespit edildi.',
            '/new\s+Function\s*\(/i' => 'Dinamik fonksiyon oluşturma tespit edildi.',
            '/\bsetTimeout\s*\(\s*.*[\'"`].*\)/i' => 'setTimeout içinde kod çalıştırma tespit edildi.',
            '/\bsetInterval\s*\(\s*.*[\'"`].*\)/i' => 'setInterval içinde kod çalıştırma tespit edildi.',
            '/\bdocument\.write\b/i' => 'document.write() kullanımı tespit edildi.',
            '/\bdocument\.createElement\([\'"]script[\'"]\)/i' => 'Dinamik script oluşturma tespit edildi.',
            '/\.appendChild\s*\(/i' => 'DOM manipülasyonu tespit edildi.',
            '/\bdata:text\/html\b/i' => 'Data URL\'si tespit edildi.',
            '/\bjavascript:\b/i' => 'javascript: protokolü tespit edildi.',
            '/\bonload\s*=/i' => 'onload event handler tespit edildi.',
            '/\bonerror\s*=/i' => 'onerror event handler tespit edildi.',
            '/\bonclick\s*=/i' => 'onclick event handler tespit edildi.',
        ];
    }

    /**
     * Facebook Pixel ID'sini çıkarır
     *
     * @param string $pixelCode
     * @return string|null
     */
    private function extractFacebookPixelId(string $pixelCode): ?string
    {
        if (preg_match('/fbq\s*\(\s*[\'"]init[\'"]\s*,\s*[\'"](\d+)[\'"]\s*\)/', $pixelCode, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Google tag ID'sini çıkarır
     *
     * @param string $pixelCode
     * @return string|null
     */
    private function extractGoogleId(string $pixelCode): ?string
    {
        if (preg_match('/[\'](UA|AW|G|GTM)-[a-zA-Z0-9\-]+[\'"]/', $pixelCode, $matches)) {
            return str_replace(['\'', '"'], '', $matches[0]);
        }

        return null;
    }
}
