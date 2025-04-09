<?php

namespace App\Services\Tenant;

use App\Models\Link;
use App\Models\Stat;
use App\Traits\AuthUser;
use Illuminate\Support\Str;
use App\Services\Tenant\UrlScanner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use GuzzleHttp\Client as HttpClient;
use Carbon\Carbon;

class LinkService
{

    use AuthUser;

    public function __construct(protected StatService $statService, protected UrlScanner $urlScanner)
    {
        $this->initializeAuthUser();
    }

    /**
     * Get all links with pagination
     */
    public function getAllLinks(int $perPage = 10): LengthAwarePaginator
    {
        return Link::with(['space', 'campaign'])->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get all active links with pagination
     */
    public function getActiveLinks(int $perPage = 10): LengthAwarePaginator
    {
        return Link::with(['space', 'campaign'])
            ->where('disabled', false)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get link by ID
     */
    public function getLinkById(int $id): ?Link
    {
        // Önce mevcut kullanıcının tenant_id'si ile sorgu yap (ek güvenlik katmanı)
        if ($this->user && $this->user->tenant_id) {
            // where('tenant_id') kullanarak tenant izolasyonunu açıkça belirt
            return Link::with(['space', 'pixels'])->where('id', $id)
                ->where('tenant_id', $this->user->tenant_id)
                ->first();
        }

        // Auth yoksa veya global admin ise, global scope'a güven
        return Link::with(['space', 'pixels'])->where('id', $id)->first();
    }

    /**
     * Get link by alias
     */
    public function getLinkByAlias(string $alias): ?Link
    {
        // Tenant ID'leri sorgulara her durumda dahil etmek için
        // Global redirect controller'lardan çağrıldığında tenant_id null olabilir
        // Bu durumda tüm tenant'ların alias'larına izin verilir (genel erişim için)
        $query = Link::where('alias', $alias);

        // Eğer kullanıcı giriş yapmışsa ve tenant ID'si varsa, sorguyu kısıtla
        if ($this->user && $this->user->tenant_id) {
            $query->where('tenant_id', $this->user->tenant_id);
        }

        return $query->first();
    }

    /**
     * Create a new link
     */
    public function createLink(array $data): Link
    {
        // URL'ye UTM parametrelerini ekle
        $url = $this->appendUtmParameters($data['url'], $data);

        // URL güvenlik taraması yap
        $scanResult = $this->urlScanner->scanUrl($url);
        if (!$scanResult['is_safe'] && !config('link-safety.allow_unsafe_urls', false)) {
            // Güvensiz URL'ler için basit metaveri kullan
            $metadata = [
                'title' => $url,
                'description' => 'Bu URL güvenlik taramasından geçemedi: ' . $scanResult['message'],
                'image' => null,
            ];
        } else {
            // URL meta verilerini çek
            $metadata = $this->parseUrl($url);
        }

        // Alias oluştur (özel veya rastgele)
        $alias = $data['alias'] ?? $this->generateAlias();

        // Link verisini hazırla
        $linkData = [
            'url' => $url,
            'alias' => $alias,
            'title' => $metadata['title'] ?? null,
            'description' => $metadata['description'] ?? null,
            'image' => $metadata['image'] ?? null,
            'password' => isset($data['password']) ? Hash::make($data['password']) : null,
            'space_id' => $data['space_id'] ?? null,
            'disabled' => $data['disabled'] ?? false,
            'expiration_url' => $data['expiration_url'] ?? null,
            'expiration_clicks' => $data['expiration_clicks'] ?? null,
            'target_type' => $data['target_type'] ?? 0,
            'goal' => $data['goal'] ?? null,
            // Hedefleme verileri direkt alınacak
            'country_target' => $data['country_target'] ?? null,
            'platform_target' => $data['platform_target'] ?? null,
            'language_target' => $data['language_target'] ?? null,
            'rotation_target' => $data['rotation_target'] ?? null,
            'utm_source' => $data['utm_source'] ?? null,
            'utm_medium' => $data['utm_medium'] ?? null,
            'utm_campaign' => $data['utm_campaign'] ?? null,
            'utm_term' => $data['utm_term'] ?? null,
            'utm_content' => $data['utm_content'] ?? null,
            'campaign_id' => $data['campaign_id'] ?? null,
        ];

        // Expire date ve time varsa ends_at hesapla
        if (!empty($data['expiration_date']) && !empty($data['expiration_time'])) {
            $linkData['ends_at'] = $data['expiration_date'] . ' ' . $data['expiration_time'];
        }

        // Published date ve time varsa published_at hesapla
        if (!empty($data['published_at_date']) && !empty($data['published_at_time'])) {
            $linkData['published_at'] = $data['published_at_date'] . ' ' . $data['published_at_time'];

            // Yayınlanma tarihi tanımlandığında, link otomatik olarak pasif olmalı
            $linkData['disabled'] = true; // Linki pasif yap
        }

        // Link oluştur
        $link = Link::create($linkData);

        // Pixel ilişkilerini ekle (varsa)
        if (!empty($data['pixel_ids'])) {
            $link->pixels()->sync($data['pixel_ids']);
        }

        return $link;
    }

    /**
     * Create multiple links at once
     */
    public function createMultipleLinks(array $urls): array
    {
        $links = [];

        foreach ($urls as $url) {
            $links[] = $this->createLink(['url' => $url]);
        }

        return $links;
    }

    /**
     * Update link's basic information
     */
    public function updateBasicInfo(int $id, array $data): Link
    {
        $link = $this->getLinkById($id);

        // Güncellenecek alanları hazırla
        $updateData = [];

        // Alias alanını kontrol et ve ekle
        if (array_key_exists('alias', $data)) {
            $updateData['alias'] = $data['alias'];
        }

        // Space ID alanını kontrol et ve ekle
        if (array_key_exists('space_id', $data)) {
            $updateData['space_id'] = $data['space_id'];
        }

        // Goal alanını kontrol et ve ekle
        if (array_key_exists('goal', $data)) {
            $updateData['goal'] = $data['goal'];
        }

        // Published_at hesapla ve ekle (tarih ve saat varsa)
        if (isset($data['published_at_date']) && isset($data['published_at_time']) && $data['published_at_date'] && $data['published_at_time']) {
            $publishedAt = $data['published_at_date'] . ' ' . $data['published_at_time'];
            $updateData['published_at'] = $publishedAt;

            // Yayınlanma tarihi tanımlandığında, link otomatik olarak pasif olmalı
            $updateData['disabled'] = true;
        } elseif (array_key_exists('published_at', $data)) {
            $updateData['published_at'] = $data['published_at'];
        } elseif ((isset($data['published_at_date']) && !$data['published_at_date']) ||
                (isset($data['published_at_time']) && !$data['published_at_time'])) {
            $updateData['published_at'] = null;
        }

        // Değişiklik varsa güncelle
        if (!empty($updateData)) {
            $link->update($updateData);
        }

        return $link;
    }

    /**
     * Toggle link status (enable/disable)
     */
    public function toggleStatus(int $id): Link
    {
        $link = $this->getLinkById($id);

        // Eğer pasiften aktife geçiyorsa ve published_at değeri varsa, temizle
        if ($link->disabled && $link->published_at) {
            $link->published_at = null;
        }

        $link->disabled = !$link->disabled;
        $link->save();

        return $link;
    }

    /**
     * Update link's extra information
     */
    public function updateExtraInfo(int $id, array $data): Link
    {
        $link = $this->getLinkById($id);

        $updateData = [];

        // Şifre kontrolü - null gelirse veya boşsa temizle
        if (array_key_exists('password', $data)) {
            if ($data['password'] === null || $data['password'] === '') {
                $updateData['password'] = null;
                $updateData['password_hint'] = null; // Şifre yoksa ipucu da olmasın
            } else {
                $updateData['password'] = Hash::make($data['password']);
                // Şifre ipucunu da güncelle (varsa)
                if (array_key_exists('password_hint', $data)) {
                    $updateData['password_hint'] = $data['password_hint'];
                }
            }
        }

        // Şifre yoksa, ama ipucu varsa
        else if (array_key_exists('password_hint', $data)) {
            $updateData['password_hint'] = null;
        }

        // Tıklama limiti kontrolü - null gelirse veya boşsa temizle
        if (array_key_exists('expiration_clicks', $data)) {
            $updateData['expiration_clicks'] = ($data['expiration_clicks'] === null || $data['expiration_clicks'] === '') ? null : $data['expiration_clicks'];
        }

        // Süre sonu URL kontrolü - null gelirse veya boşsa temizle
        if (array_key_exists('expiration_url', $data)) {
            $updateData['expiration_url'] = ($data['expiration_url'] === null || $data['expiration_url'] === '') ? null : $data['expiration_url'];
        }

        // Son kullanım tarihi işleme
        if (array_key_exists('expiration_date', $data) || array_key_exists('expiration_time', $data)) {
            $date = $data['expiration_date'] ?? '';
            $time = $data['expiration_time'] ?? '';

            if (empty($date) || empty($time)) {
                $updateData['ends_at'] = null;
            } else {
                $updateData['ends_at'] = $date . ' ' . $time;
            }
        }

        // Değişiklik varsa güncelle
        if (!empty($updateData)) {
            $link->update($updateData);
        }

        // Pixel ilişkilerini güncelle (varsa)
        if (array_key_exists('pixel_ids', $data)) {
            $link->pixels()->sync($data['pixel_ids'] ?: []);
        }

        return $link;
    }

    /**
     * Update link's UTM parameters
     */
    public function updateUtmInfo(int $id, array $data): Link
    {
        $link = $this->getLinkById($id);

        // Güncellenecek UTM verilerini hazırla
        $updateData = [
            'utm_source' => $data['utm_source'] ?? null,
            'utm_medium' => $data['utm_medium'] ?? null,
            'utm_campaign' => $data['utm_campaign'] ?? null,
            'utm_term' => $data['utm_term'] ?? null,
            'utm_content' => $data['utm_content'] ?? null,
        ];

        // Güncelleme işlemini gerçekleştir
        $link->forceFill($updateData);
        $link->save();

        return $link;
    }

    public function updateTargetInfo(int $id, array $data): Link
    {

        $link = $this->getLinkById($id);

        $updateData = [
            'target_type' => $data['target_type'] ?? 0,
        ];

        // Ülke hedefleme verileri kontrol edilir ve güncellenir
        if (isset($data['country_target'])) {
            $updateData['country_target'] = $data['country_target'];
        }

        // Platform hedefleme verileri kontrol edilir ve güncellenir
        if (isset($data['platform_target'])) {
            $updateData['platform_target'] = $data['platform_target'];
        }

        // Dil hedefleme verileri kontrol edilir ve güncellenir
        if (isset($data['language_target'])) {
            $updateData['language_target'] = $data['language_target'];
        }

        // Rotasyon hedefleme verileri kontrol edilir ve güncellenir
        if (isset($data['rotation_target'])) {
            $updateData['rotation_target'] = $data['rotation_target'];
        }

        $link->update($updateData);
        return $link;
    }

    /**
     * Delete a link
     */
    public function deleteLink(int $id): bool
    {
        return Link::findOrFail($id)->delete();
    }

    /**
     * Increment link clicks
     */
    public function incrementClicks(int $id): void
    {
        Link::where('id', $id)->increment('clicks');
    }

    /**
     * Update link's last rotation
     */
    public function updateLastRotation(int $id, int $lastRotation): void
    {
        Link::where('id', $id)->update(['last_rotation' => $lastRotation]);
    }

    /**
     * Generate a unique alias
     */
    private function generateAlias(): string
    {
        $alias = Str::random(10);

        while (Link::where('alias', $alias)->exists()) {
            $alias = Str::random(10);
        }

        return $alias;
    }

    /**
     * Append UTM parameters to the URL
     */
    private function appendUtmParameters(string $url, array $data): string
    {
        $utmParams = [];

        foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'] as $param) {
            if (isset($data[$param]) && $data[$param] !== '') {
                $utmParams[$param] = $data[$param];
            }
        }

        if (count($utmParams) > 0) {
            $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . http_build_query($utmParams);
        }

        return $url;
    }

    /**
     * Parse URL for metadata
     */
    private function parseUrl(string $url): array
    {
        $httpClient = new HttpClient([
            'timeout' => 5, // Kısa timeout ile
            'connect_timeout' => 3,
        ]);

        $metadata = [
            'title' => null,
            'description' => null,
            'image' => null,
        ];

        try {
            $response = $httpClient->get($url);
            $html = $response->getBody()->getContents();

            // Meta tag'leri çek
            preg_match('/<title>(.*?)<\/title>/s', $html, $titleMatches);
            preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\'](.*?)["\'].*?>/si', $html,
$descriptionMatches);
preg_match('/<meta[^>]*property=["\']og:image["\'][^>]*content=["\'](.*?)["\'].*?>/si', $html, $imageMatches);

    if (!empty($titleMatches[1])) {
    $metadata['title'] = $titleMatches[1];
    }

    if (!empty($descriptionMatches[1])) {
    $metadata['description'] = $descriptionMatches[1];
    }

    if (!empty($imageMatches[1])) {
    $metadata['image'] = $imageMatches[1];
    }
    } catch (\Exception $e) {
    // Hata olursa sessizce devam et
    }

    return $metadata;
    }
    }
