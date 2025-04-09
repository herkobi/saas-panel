<?php

namespace App\Services\Tenant;

use App\Enums\StatType;
use App\Models\Campaign;
use App\Models\Link;
use App\Models\Pixel;
use App\Models\Space;
use App\Traits\AuthUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    use AuthUser;

    public function __construct(protected StatService $statService)
    {
        $this->initializeAuthUser();
    }

    /**
     * Dashboard için özet istatistikleri al
     */
    public function getSummaryStats(): array
    {
        return [
            'totalLinks' => Link::count(),
            'totalSpaces' => Space::count(),
            'totalPixels' => Pixel::count(),
            'totalCampaigns' => Campaign::count(),
        ];
    }

    /**
     * Son eklenen linkleri al
     */
    public function getRecentLinks(int $limit = 5): object
    {
        return Link::with(['space'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($link) {
                return [
                    'id' => $link->id,
                    'url' => $link->url,
                    'alias' => $link->alias,
                    'clicks' => $link->clicks,
                    'space' => $link->space ? [
                        'id' => $link->space->id,
                        'name' => $link->space->name,
                        'color' => $link->space->color,
                    ] : null,
                    'created_at' => $link->created_at->diffForHumans(),
                ];
            });
    }

    /**
     * En çok tıklanan linkleri al
     */
    public function getPopularLinks(int $limit = 5): object
    {
        return Link::orderBy('clicks', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($link) {
                return [
                    'id' => $link->id,
                    'url' => $link->url,
                    'alias' => $link->alias,
                    'clicks' => $link->clicks,
                    'created_at' => $link->created_at->diffForHumans(),
                ];
            });
    }

    /**
     * Belirli bir tarih aralığındaki günlük tıklama istatistiklerini al
     */
    public function getDailyClicksStats(string $startDate, string $endDate): array
    {
        $stats = DB::table('stats')
            ->join('links', 'stats.link_id', '=', 'links.id')
            ->where('stats.name', StatType::CLICKS)
            ->whereDate('stats.date', '>=', $startDate)
            ->whereDate('stats.date', '<=', $endDate);

        // Tenant ID kontrolü
        if ($this->user && $this->user->tenant_id) {
            $stats->where('links.tenant_id', $this->user->tenant_id);
        }

        $stats = $stats->select(
                DB::raw('DATE(stats.date) as date'),
                DB::raw('SUM(stats.count) as total_clicks')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Tüm tarihler için başlangıç değerleri
        $clicksByDate = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current->lte($end)) {
            $dateKey = $current->format('Y-m-d');
            $clicksByDate[$dateKey] = 0;
            $current->addDay();
        }

        // DB'den gelen değerlerle güncelle
        foreach ($stats as $stat) {
            $clicksByDate[$stat->date] = (int)$stat->total_clicks;
        }

        return $clicksByDate;
    }

    /**
     * En çok kullanılan alanları al
     */
    public function getTopSpaces(int $limit = 3): object
    {
        return Space::withCount('links')
            ->orderBy('links_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($space) {
                return [
                    'id' => $space->id,
                    'name' => $space->name,
                    'color' => $space->color,
                    'links_count' => $space->links_count,
                ];
            });
    }

    /**
     * Yaklaşan etkinlikleri al
     *
     * @param int $limit Her kategori için maksimum etkinlik sayısı
     * @return array Zamanlanmış linkler, sona eren kampanyalar ve yakında başlayacak kampanyalar bilgileri
     */
    public function getUpcomingEvents(int $limit = 3): array
    {
        // Şu anki tarih
        $now = Carbon::now();

        // 1. Zamanlanmış linkler (published_at tarihi gelecekte olan pasif linkler)
        $scheduledLinks = Link::where('disabled', true)
            ->whereNotNull('published_at')
            ->where('published_at', '>', $now)
            ->orderBy('published_at')
            ->limit($limit)
            ->get()
            ->map(function ($link) {
                return [
                    'id' => $link->id,
                    'title' => $link->title ?: $link->alias,
                    'alias' => $link->alias,
                    'type' => 'scheduled_link',
                    'date' => $link->published_at->format('d F Y'),
                    'relative_date' => $link->published_at->diffForHumans(),
                    'description' => 'Zamanlanmış link yayını',
                ];
            });

        // 2. Yakında sona erecek kampanyalar (bitiş tarihi 7 gün içinde olan aktif kampanyalar)
        $endingSoonCampaigns = Campaign::where('status', 1) // ACTIVE
            ->whereNotNull('end_date')
            ->where('end_date', '>', $now)
            ->where('end_date', '<', $now->copy()->addDays(7))
            ->orderBy('end_date')
            ->limit($limit)
            ->get()
            ->map(function ($campaign) use ($now) {
                $daysLeft = $now->diffInDays($campaign->end_date);
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'type' => 'ending_campaign',
                    'date' => $campaign->end_date->format('d F Y'),
                    'days_left' => $daysLeft,
                    'relative_date' => "Son {$daysLeft} gün",
                    'description' => $campaign->end_date->format('d F Y') . "'te sona erecek",
                ];
            });

        // 3. Yakında başlayacak kampanyalar (başlangıç tarihi gelecekte olan kampanyalar)
        $upcomingCampaigns = Campaign::whereNotNull('start_date')
            ->where('start_date', '>', $now)
            ->where('start_date', '<', $now->copy()->addDays(14))
            ->orderBy('start_date')
            ->limit($limit)
            ->get()
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'type' => 'upcoming_campaign',
                    'date' => $campaign->start_date->format('d F Y'),
                    'relative_date' => $campaign->start_date->diffForHumans(),
                    'description' => 'Otomatik yayınlanacak kampanya',
                ];
            });

        return [
            'scheduled_links' => $scheduledLinks,
            'ending_campaigns' => $endingSoonCampaigns,
            'upcoming_campaigns' => $upcomingCampaigns,
        ];
    }

    /**
     * Dashboard için popüler tarayıcı, cihaz ve ülke istatistiklerini al
     */
    public function getQuickStats(): array
    {
        $now = Carbon::now();
        $startDate = $now->copy()->subDays(30)->format('Y-m-d');
        $endDate = $now->format('Y-m-d');

        // Tüm tenant linklerini kullanarak en popüler tarayıcı, cihaz ve ülkelerini bul
        $query = function($statType) use ($startDate, $endDate) {
            return DB::table('stats')
                ->join('links', 'stats.link_id', '=', 'links.id')
                ->where('stats.name', $statType)
                ->whereDate('stats.date', '>=', $startDate)
                ->whereDate('stats.date', '<=', $endDate)
                ->when($this->user && $this->user->tenant_id, function($q) {
                    return $q->where('links.tenant_id', $this->user->tenant_id);
                })
                ->select('stats.value', DB::raw('SUM(stats.count) as total'))
                ->groupBy('stats.value')
                ->orderBy('total', 'desc')
                ->first();
        };

        // En popüler cihaz tipini al (mobil, masaüstü, tablet)
        $topDevice = $query(StatType::DEVICE);

        // En popüler tarayıcıyı al
        $topBrowser = $query(StatType::BROWSER);

        // En popüler ülkeyi al
        $topCountry = $query(StatType::COUNTRY);

        // Toplam tıklama sayısını al
        $totalClicks = DB::table('stats')
            ->join('links', 'stats.link_id', '=', 'links.id')
            ->where('stats.name', StatType::CLICKS)
            ->whereDate('stats.date', '>=', $startDate)
            ->whereDate('stats.date', '<=', $endDate)
            ->when($this->user && $this->user->tenant_id, function($q) {
                return $q->where('links.tenant_id', $this->user->tenant_id);
            })
            ->sum('stats.count');

        // Her kategori için yüzdeyi hesapla
        $getPercentage = function($stat) use ($totalClicks) {
            if (!$stat || $totalClicks == 0) return 0;
            return round(($stat->total / $totalClicks) * 100);
        };

        return [
            'device' => $topDevice ? [
                'name' => $topDevice->value,
                'percentage' => $getPercentage($topDevice)
            ] : ['name' => 'Bilinmiyor', 'percentage' => 0],

            'browser' => $topBrowser ? [
                'name' => $topBrowser->value,
                'percentage' => $getPercentage($topBrowser)
            ] : ['name' => 'Bilinmiyor', 'percentage' => 0],

            'country' => $topCountry ? [
                'name' => $topCountry->value,
                'percentage' => $getPercentage($topCountry)
            ] : ['name' => 'Bilinmiyor', 'percentage' => 0],
        ];
    }
}
