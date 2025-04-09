<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Link\ValidateLinkPasswordRequest;
use App\Models\PixelConsentLog;
use App\Services\Tenant\LinkService;
use App\Services\Tenant\StatService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;
use WhichBrowser\Parser as UserAgent;
use GeoIp2\Database\Reader as GeoIP;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class RedirectController extends Controller
{
    public function __construct(protected LinkService $linkService, protected StatService $statService)
    {
    }

    /**
     * Handle the Redirect.
     */
    public function index(Request $request, string $alias)
    {
        // Doğrudan link arama - LinkService üzerinden kontrol
        $link = $this->linkService->getLinkByAlias($alias);

        // Link yoksa 404 göster
        if (!$link) {
            abort(404);
        }

        // Reserved paths kontrolü
        $reservedPaths = ['app', 'panel', 'agreement'];
        if (in_array(strtolower($alias), $reservedPaths)) {
            abort(404);
        }

        // Link veya tenant devre dışı bırakılmışsa
        if ($link->disabled) {
            return Inertia::render('front/redirect/Disabled', [
                'link' => [
                    'id' => $link->id,
                    'alias' => $link->alias,
                ]
            ]);
        }

        // Yasaklı kelimeleri kontrol et
        $bannedWords = config('data.badwords') ?? [];
        foreach($bannedWords as $word) {
            if(strpos(mb_strtolower($link->url), mb_strtolower($word)) !== false) {
                return Inertia::render('front/redirect/Banned', [
                    'link' => [
                        'id' => $link->id,
                        'alias' => $link->alias,
                    ]
                ]);
            }
        }

        $referrer = parse_url($request->server('HTTP_REFERER'), PHP_URL_HOST) ?? null;

        // Önizleme isteği kontrolü
        if ($request->has('preview')) {
            return Inertia::render('front/redirect/Preview', [
                'link' => [
                    'id' => $link->id,
                    'alias' => $link->alias,
                    'url' => $link->url,
                    'title' => $link->title,
                    'description' => $link->description,
                ]
            ]);
        }

        // Şifre koruma kontrolü
        if ($link->password && !$request->session()->has('verified_link_' . $link->id)) {
            $request->session()->put('referrer_' . $link->id, $referrer);
            return Inertia::render('front/redirect/Password', [
                'link' => [
                    'id' => $link->id,
                    'alias' => $link->alias,
                    'password_hint' => $link->password_hint,
                ]
            ]);
        } elseif ($link->password && $request->session()->has('verified_link_' . $link->id)) {
            $referrer = $request->session()->get('referrer_' . $link->id, $referrer);
            $request->session()->forget('referrer_' . $link->id); // Kullanıldıktan sonra temizle
        }

        $now = Carbon::now();

        // Link süresi dolmuş mu?
        if ($link->ends_at !== null && $now->greaterThan($link->ends_at)) {
            if ($link->expiration_url) {
                return redirect()->to($link->expiration_url, 301)
                    ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
            }

            return Inertia::render('front/redirect/Expired', [
                'link' => [
                    'id' => $link->id,
                    'alias' => $link->alias,
                ]
            ]);
        }

        // Tıklama sınırı aşılmış mı?
        if ($link->expiration_clicks && $link->clicks >= $link->expiration_clicks) {
            if ($link->expiration_url) {
                return redirect()->to($link->expiration_url, 301)
                    ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
            }

            return Inertia::render('front/redirect/Expired', [
                'link' => [
                    'id' => $link->id,
                    'alias' => $link->alias,
                ]
            ]);
        }

        // Piksel izleme onayı kontrolü
        if (count($link->pixels) > 0 && !$request->cookie('consent_' . $link->id)) {
            $request->session()->put('referrer_' . $link->id, $referrer);
            return Inertia::render('front/redirect/Consent', [
                'link' => [
                    'id' => $link->id,
                    'alias' => $link->alias,
                    'pixels' => $link->pixels,
                ]
            ]);
        } elseif (count($link->pixels) > 0 && $request->cookie('consent_' . $link->id)) {
            $referrer = $request->session()->get('referrer_' . $link->id, $referrer);
            $request->session()->forget('referrer_' . $link->id); // Kullanıldıktan sonra temizle
        }

        // Kullanıcı ajanını al
        $ua = new UserAgent(getallheaders());

        // Bot kontrolü
        if ($ua->device->type == 'bot') {
            return redirect()->to($this->urlParamsForward($link->url), 301)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }

        // Kullanıcı konumu al
        $countryCode = $country = $city = null;

        // Yerel ortamda mı kontrol et
        $isLocalEnvironment = in_array($request->getHost(), ['localhost', '127.0.0.1']) ||
                        str_ends_with($request->getHost(), '.test') ||
                        str_ends_with($request->getHost(), '.local');

        if ($isLocalEnvironment) {
            // Test için varsayılan değerler
            $countryCode = 'TR';
            $country = 'TR:Türkiye';
            $city = 'TR:İstanbul, 34';
        } else {
            try {
                // Canlı ortamda gerçek GeoIP sorgusu
                $geoip = new GeoIP(storage_path('app/geoip/GeoLite2-City.mmdb'));
                $geoipData = $geoip->city($request->ip());

                $countryCode = $geoipData->country->isoCode ?? null;
                $country = isset($geoipData->country->isoCode) ? $geoipData->country->isoCode . ':' . $geoipData->country->name : null;
                $city = isset($geoipData->city->name) && isset($geoipData->country->isoCode) ?
                        $geoipData->country->isoCode . ':' . $geoipData->city->name .
                        (isset($geoipData->mostSpecificSubdivision->isoCode) ? ', ' . $geoipData->mostSpecificSubdivision->isoCode : '') : null;
            } catch (\Exception $e) {
                // Hata durumunda zaten varsayılan değerler null olarak kalacak
            }
        }

        $date = $now->format('Y-m-d');
        $time = $now->format('H');

        // İstatistik verilerini hazırla
        $data = [
            'link_id' => $link->id,
            'country' => $country,
            'city' => $city,
            'browser' => $ua->browser->name ?? null,
            'platform' => $ua->os->name ?? null,
            'device' => $ua->device->type ?? null,
            'language' => isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null,
            'clicks' => $date,
            'clicks_hour' => $time,
            'referrer' => $referrer,
            'date' => $date
        ];

        // LinkService kullanarak tıklama sayısını artır
        $this->linkService->incrementClicks($link->id);

        // StatService kullanarak istatistik verilerini kaydet
        $this->statService->saveStats($data);

        // Yönlendirilecek URL
        $url = $link->url;

        // Coğrafi hedefleme
        if ($link->target_type == 1 && $link->country_target !== null) {
            if ($link->country_target) {
                foreach ($link->country_target as $country) {
                    if ($countryCode == $country['key']) {
                        $url = $country['value'];
                    }
                }
            }
        }

        // Platform hedefleme
        if ($link->target_type == 2 && $link->platform_target !== null) {
            if ($link->platform_target) {
                foreach ($link->platform_target as $platform) {
                    if ($data['platform'] == $platform['key']) {
                        $url = $platform['value'];
                    }
                }
            }
        }

        // Dil hedefleme
        if ($link->target_type == 3 && $link->language_target !== null) {
            if ($link->language_target) {
                foreach ($link->language_target as $language) {
                    if ($data['language'] == $language['key']) {
                        $url = $language['value'];
                    }
                }
            }
        }

        // Rotasyon hedefleme
        if ($link->target_type == 4 && $link->rotation_target !== null) {
            $totalRotations = count($link->rotation_target);

            $last_rotation = 0;
            if ($totalRotations > 0 && $totalRotations > $link->last_rotation) {
                $last_rotation = $link->last_rotation + 1;
            }

            $this->linkService->updateLastRotation($link->id, $last_rotation);

            if (isset($link->rotation_target[$link->last_rotation])) {
                $url = $link->rotation_target[$link->last_rotation]['value'];
            }
        }

        // Piksel izleme kontrolü
        if (count($link->pixels) > 0 && $request->cookie('consent_' . $link->id) == 1) {
            return Inertia::render('front/redirect/Redirect', [
                'link' => [
                    'id' => $link->id,
                    'alias' => $link->alias,
                    'pixels' => $link->pixels,
                ],
                'url' => $url
            ]);
        }

        return redirect()->to($this->urlParamsForward($url), 301)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    /**
     * Validate the link's password
     */
    public function validatePassword(ValidateLinkPasswordRequest $request, string $id)
    {
        $link = $this->linkService->getLinkByAlias($id);

        if (!$link) {
            return back()->withErrors(['password' => 'Link bulunamadı.']);
        }

        // Şifre kontrolü
        if (!Hash::check($request->password, $link->password)) {
            return back()->withErrors(['password' => 'Şifre hatalı.']);
        }

        // Session'a doğrulama bilgisini ekle
        $request->session()->put('verified_link_' . $link->id, true);

        // Kullanıcıyı index metoduna yönlendir
        return redirect()->route('redirect', $id);
    }

    /**
     * Validate the link's consent
     */
    public function validateConsent(Request $request, string $id)
    {
        $link = $this->linkService->getLinkByAlias($id);

        if (!$link) {
            return response()->json(['success' => false, 'message' => 'Link bulunamadı.'], 404);
        }

        $consent = $request->input('consent', -1);

        // Consent log kaydı oluştur
        PixelConsentLog::create([
            'tenant_id' => $link->tenant_id,
            'link_id' => $link->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'consent_status' => (bool) $consent,
            'referer' => $request->header('referer'),
            'consent_timestamp' => now()
        ]);

        // Cookie oluştur
        $cookie = cookie('consent_' . $link->id, $consent, 60 * 24 * 30);

        return redirect()->route('redirect', $id)->withCookie($cookie);
    }

    /**
     * Format a URL to append additional parameters
     */
    private function urlParamsForward(string $url): string
    {
        $forwardParams = request()->all();

        // Ek parametreler varsa
        if ($forwardParams) {
            $urlParts = parse_url($url);

            // Orijinal parametreleri ayır
            $originalParams = [];
            if (isset($urlParts['query'])) {
                parse_str($urlParts['query'], $originalParams);
            }

            // Orijinal parametreleri yenileriyle birleştir
            $parsedParams = array_merge($originalParams, $forwardParams);

            // URL oluştur
            $scheme = $urlParts['scheme'] ?? 'http';
            $host = $urlParts['host'] ?? '';
            $path = $urlParts['path'] ?? '/';

            $url = $scheme . '://' . $host . $path . '?' . http_build_query($parsedParams);

            return $url;
        }

        return $url;
    }
}
