<?php

namespace App\Services\Tenant;

use Carbon\Carbon;
use App\Models\Link;
use App\Models\Stat;
use App\Enums\StatType;
use App\Traits\AuthUser;
use App\Exports\LinkStatsExport;
use Illuminate\Support\Facades\DB;
use App\Exports\LinkStatsCsvExport;
use App\Exports\LinkStatsPdfExport;
use Maatwebsite\Excel\Facades\Excel;

class StatService
{
    use AuthUser;

    public function __construct()
    {
        $this->initializeAuthUser();
    }

    /**
     * Save link statistics
     */
    public function saveStats(array $data): void
    {
        $linkId = $data['link_id'];
        $date = $data['date'];
        unset($data['link_id'], $data['date']);

        // Link ID'nin geçerli bir tenant'a ait olduğunu kontrol et
        if ($this->user && $this->user->tenant_id) {
            // Önce link'in kullanıcının tenant'ına ait olduğunu doğrula
            $link = Link::where('id', $linkId)
                ->where('tenant_id', $this->user->tenant_id)
                ->first();

            // Link yoksa veya farklı tenant'a aitse işlemi durdur
            if (!$link) {
                return;
            }
        }

        foreach ($data as $name => $value) {
            if ($value !== null) {
                $this->updateOrCreateStat([
                    'link_id' => $linkId,
                    'name' => $name,
                    'value' => mb_substr((string)$value, 0, 255),
                    'date' => $date
                ]);
            }
        }
    }

    /**
     * Update or create a stat record
     */
    private function updateOrCreateStat(array $data): Stat
    {
        $stat = Stat::where([
            'link_id' => $data['link_id'],
            'name' => $data['name'],
            'value' => $data['value'],
            'date' => $data['date']
        ])->first();

        if ($stat) {
            // Mevcut kaydı güncelle
            $stat->increment('count');
            return $stat;
        } else {
            // Yeni kayıt oluştur
            return Stat::create([
                'link_id' => $data['link_id'],
                'name' => $data['name'],
                'value' => $data['value'],
                'date' => $data['date'],
                'count' => 1
            ]);
        }
    }

    /**
     * Get unique clicks (approximation)
     */
    public function getUniqueClicks(int $linkId): int
    {
        // Önce link'in tenant'ını kontrol et
        if ($this->user && $this->user->tenant_id) {
            $link = Link::where('id', $linkId)
                ->where('tenant_id', $this->user->tenant_id)
                ->first();

            if (!$link) {
                return 0;
            }
        }

        // Benzersiz ziyaretçi sayısını tahmin et
        $uniqueVisitors = Stat::where('link_id', $linkId)
            ->where(function($query) {
                $query->where('name', StatType::REFERRER)
                    ->orWhere('name', StatType::BROWSER)
                    ->orWhere('name', StatType::PLATFORM);
            })
            ->distinct('value')
            ->count('value');

        // En azından tekil ziyaretçi sayısı toplam tıklamadan fazla olmamalı
        $totalClicks = $this->getTotalClicksAllTime($linkId);
        return min($uniqueVisitors, $totalClicks);
    }

    /**
     * Get total clicks for all time
     */
    public function getTotalClicksAllTime(int $linkId): int
    {
        // Önce link'in tenant'ını kontrol et
        if ($this->user && $this->user->tenant_id) {
            $link = Link::where('id', $linkId)
                ->where('tenant_id', $this->user->tenant_id)
                ->first();

            if (!$link) {
                return 0;
            }
        }

        return Stat::where('link_id', $linkId)
            ->where('name', StatType::CLICKS)
            ->sum('count');
    }

    /**
     * Get conversion rate
     */
    public function getConversionRate(int $linkId): string
    {
        // Önce link'in tenant'ını kontrol et
        if ($this->user && $this->user->tenant_id) {
            $link = Link::where('id', $linkId)
                ->where('tenant_id', $this->user->tenant_id)
                ->first();

            if (!$link) {
                return '0%';
            }
        }

        $uniqueClicks = $this->getUniqueClicks($linkId);
        $totalClicks = $this->getTotalClicksAllTime($linkId);

        if ($totalClicks == 0) {
            return '0%';
        }

        $rate = ($uniqueClicks / $totalClicks) * 100;
        return number_format($rate, 1) . '%';
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
     * Get link summary stats
     */
    public function getLinkSummaryStats(Link $link): array
    {
        // Önce link'in tenant'ını kontrol et
        if ($this->user && $this->user->tenant_id && $link->tenant_id != $this->user->tenant_id) {
            // Farklı tenant'a ait link için boş veri döndür
            return [
                'totalClicks' => 0,
                'clicksMap' => [],
                'topReferrers' => collect([]),
                'topCountries' => collect([]),
                'topBrowsers' => collect([]),
                'topPlatforms' => collect([]),
                'dateRange' => [
                    'from' => Carbon::now()->subDays(6)->format('Y-m-d'),
                    'to' => Carbon::now()->format('Y-m-d')
                ]
            ];
        }

        // Son 7 günün tarih aralığı
        $to = Carbon::now()->format('Y-m-d');
        $from = Carbon::now()->subDays(6)->format('Y-m-d');

        // Toplam tıklama sayısı
        $totalClicks = $this->getTotalClicks($link->id, $from, $to);

        // Son 7 günlük tıklama trendi (günlük)
        $clicksMap = $this->getClicksMap($link->id, $from, $to, 'day');

        // En çok tıklama alan referrerlar (en fazla 3)
        $topReferrers = $this->getTopReferrers($link->id, $from, $to, 3);

        // En çok tıklama alan ülkeler (en fazla 3)
        $topCountries = $this->getTopCountries($link->id, $from, $to, 3);

        // Tarayıcı dağılımı (en fazla 3)
        $topBrowsers = $this->getTopBrowsers($link->id, $from, $to, 3);

        // Platform dağılımı (en fazla 3)
        $topPlatforms = $this->getTopPlatforms($link->id, $from, $to, 3);

        return [
            'totalClicks' => $totalClicks,
            'clicksMap' => $clicksMap,
            'topReferrers' => $topReferrers,
            'topCountries' => $topCountries,
            'topBrowsers' => $topBrowsers,
            'topPlatforms' => $topPlatforms,
            'dateRange' => [
                'from' => $from,
                'to' => $to
            ]
        ];
    }

    /**
     * Get total clicks for date range
     */
    public function getTotalClicks(int $linkId, string $from, string $to): int
    {
        return Stat::where('link_id', $linkId)
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->where('name', StatType::CLICKS)
            ->sum('count');
    }

    /**
     * Get clicks map
     */
    public function getClicksMap(int $linkId, string $from, string $to, string $unit = 'day'): array
    {
        $stats = Stat::where('link_id', $linkId)
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->where('name', $unit === 'day' ? StatType::CLICKS : StatType::CLICKS_HOUR)
            ->orderBy('date')
            ->get();

        $clicksMap = [];

        // Tüm tarih aralığı için başlangıç değerleri (0)
        $startDate = Carbon::parse($from);
        $endDate = Carbon::parse($to);
        $diffDays = $endDate->diffInDays($startDate) + 1;

        for ($i = 0; $i < $diffDays; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $clicksMap[$date] = 0;
        }

        // Mevcut değerlerle güncelleme
        foreach ($stats as $stat) {
            $dateKey = $stat->date->format('Y-m-d');

            if (isset($clicksMap[$dateKey])) {
                $clicksMap[$dateKey] += $stat->count;
            } else {
                $clicksMap[$dateKey] = $stat->count;
            }
        }

        return $clicksMap;
    }

    /**
     * Get top referrers
     */
    public function getTopReferrers(int $linkId, string $from, string $to, int $limit = 10): object
    {
        return Stat::where('link_id', $linkId)
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->where('name', StatType::REFERRER)
            ->select('value', DB::raw('SUM(count) as count'))
            ->groupBy('value')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get top countries
     */
    public function getTopCountries(int $linkId, string $from, string $to, int $limit = 10): object
    {
        return Stat::where('link_id', $linkId)
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->where('name', StatType::COUNTRY)
            ->select('value', DB::raw('SUM(count) as count'))
            ->groupBy('value')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get top browsers
     */
    public function getTopBrowsers(int $linkId, string $from, string $to, int $limit = 10): object
    {
        return Stat::where('link_id', $linkId)
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->where('name', StatType::BROWSER)
            ->select('value', DB::raw('SUM(count) as count'))
            ->groupBy('value')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get top platforms
     */
    public function getTopPlatforms(int $linkId, string $from, string $to, int $limit = 10): object
    {
        return Stat::where('link_id', $linkId)
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->where('name', StatType::PLATFORM)
            ->select('value', DB::raw('SUM(count) as count'))
            ->groupBy('value')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get detailed statistics for a date range
     */
    public function getDetailedStats(int $linkId, string $from, string $to): array
    {
        // Önce link'in tenant'ını kontrol et
        if ($this->user && $this->user->tenant_id) {
            $link = Link::where('id', $linkId)
                ->where('tenant_id', $this->user->tenant_id)
                ->first();

            if (!$link) {
                // Farklı tenant'a ait link için boş veri döndür
                return [
                    'totalClicks' => 0,
                    'uniqueClicks' => 0,
                    'dailyClicks' => [],
                    'deviceStats' => collect([]),
                    'browserStats' => collect([]),
                    'platformStats' => collect([]),
                    'countryStats' => collect([]),
                    'cityStats' => collect([]),
                    'referrerStats' => collect([]),
                    'hourlyStats' => array_fill(0, 24, 0),
                    'dateRange' => [
                        'from' => $from,
                        'to' => $to
                    ]
                ];
            }
        }

        // Toplam tıklama
        $totalClicks = $this->getTotalClicks($linkId, $from, $to);

        // Benzersiz tıklamalar
        $uniqueClicks = $this->getUniqueClicksForRange($linkId, $from, $to);

        // Günlük tıklama grafiği için veri
        $dailyClicks = $this->getDailyClicks($linkId, $from, $to);

        // Cihaz dağılımı
        $deviceStats = $this->getDeviceStats($linkId, $from, $to);

        // Tarayıcı dağılımı
        $browserStats = $this->getTopBrowsers($linkId, $from, $to, 10);

        // Platform dağılımı
        $platformStats = $this->getTopPlatforms($linkId, $from, $to, 10);

        // Ülke dağılımı
        $countryStats = $this->getTopCountries($linkId, $from, $to, 10);

        // Şehir dağılımı
        $cityStats = $this->getTopCities($linkId, $from, $to, 10);

        // Kaynak site dağılımı
        $referrerStats = $this->getTopReferrers($linkId, $from, $to, 10);

        // Saat bazında tıklama dağılımı
        $hourlyStats = $this->getHourlyStats($linkId, $from, $to);

        return [
            'totalClicks' => $totalClicks,
            'uniqueClicks' => $uniqueClicks,
            'dailyClicks' => $dailyClicks,
            'deviceStats' => $deviceStats,
            'browserStats' => $browserStats,
            'platformStats' => $platformStats,
            'countryStats' => $countryStats,
            'cityStats' => $cityStats,
            'referrerStats' => $referrerStats,
            'hourlyStats' => $hourlyStats,
            'dateRange' => [
                'from' => $from,
                'to' => $to
            ]
        ];
    }

    /**
     * Get unique clicks for a specific date range
     */
    public function getUniqueClicksForRange(int $linkId, string $from, string $to): int
    {
        // Benzersiz ziyaretçileri hesapla
        $uniqueCount = Stat::where('link_id', $linkId)
            ->where(function($query) use ($from, $to) {
                $query->whereDate('date', '>=', $from)
                    ->whereDate('date', '<=', $to);
            })
            ->where(function($query) {
                $query->where('name', StatType::REFERRER)
                    ->orWhere('name', StatType::BROWSER)
                    ->orWhere('name', StatType::PLATFORM);
            })
            ->distinct('value')
            ->count('value');

        // Toplam tıklamayı geçmemeli
        $totalClicks = $this->getTotalClicks($linkId, $from, $to);
        return min($uniqueCount, $totalClicks);
    }

    /**
     * Get daily clicks
     */
    public function getDailyClicks(int $linkId, string $from, string $to): array
    {
        // Tarih aralığındaki günlere ait tıklama verileri
        $stats = Stat::where('link_id', $linkId)
            ->select('date', DB::raw('SUM(count) as total'))
            ->where('name', StatType::CLICKS)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Tarih aralığındaki her gün için bir değer oluştur
        $dailyData = [];
        $current = Carbon::parse($from);
        $end = Carbon::parse($to);

        while ($current->lte($end)) {
            $date = $current->format('Y-m-d');
            $dailyData[$date] = 0;
            $current->addDay();
        }

        // Veritabanından gelen verileri ekle
        foreach ($stats as $stat) {
            $dateKey = $stat->date->format('Y-m-d');
            $dailyData[$dateKey] = $stat->total;
        }

        return $dailyData;
    }

    /**
     * Get device type distribution
     */
    public function getDeviceStats(int $linkId, string $from, string $to): object
    {
        return Stat::where('link_id', $linkId)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->where('name', StatType::DEVICE)
            ->select('value', DB::raw('SUM(count) as count'))
            ->groupBy('value')
            ->orderBy('count', 'desc')
            ->get();
    }

/**
     * Get top cities
     */
    public function getTopCities(int $linkId, string $from, string $to, int $limit = 10): object
    {
        return Stat::where('link_id', $linkId)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->where('name', StatType::CITY)
            ->select('value', DB::raw('SUM(count) as count'))
            ->groupBy('value')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get hourly clicks distribution
     */
    public function getHourlyStats(int $linkId, string $from, string $to): array
    {
        $stats = Stat::where('link_id', $linkId)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->where('name', StatType::CLICKS_HOUR)
            ->select('value', DB::raw('SUM(count) as count'))
            ->groupBy('value')
            ->orderBy('value')
            ->get();

        // Tüm saat dilimlerini doldur (0-23)
        $hourly = array_fill(0, 24, 0);

        foreach ($stats as $stat) {
            $hour = (int)$stat->value;
            if ($hour >= 0 && $hour < 24) {
                $hourly[$hour] = $stat->count;
            }
        }

        return $hourly;
    }

    /**
     * Prepare link data for exports
     */
    private function prepareLinkData(Link $link): array
    {
        return [
            'id' => $link->id,
            'url' => $link->url,
            'alias' => $link->alias,
            'title' => $link->title,
            'description' => $link->description,
            'clicks' => $link->clicks
        ];
    }

    /**
     * Export statistics data to Excel
     */
    public function exportStatsToExcel(Link $link, string $from, string $to)
    {
        $stats = $this->getDetailedStats($link->id, $from, $to);
        $linkData = $this->prepareLinkData($link);
        $dateRange = ['from' => $from, 'to' => $to];

        return Excel::download(
            new LinkStatsExport($stats, $linkData, $dateRange),
            'link_stats_' . $link->alias . '_' . date('Ymd') . '.xlsx'
        );
    }

    /**
     * Export statistics data to CSV
     */
    public function exportStatsToCsv(Link $link, string $from, string $to)
    {
        $stats = $this->getDetailedStats($link->id, $from, $to);
        $linkData = $this->prepareLinkData($link);
        $dateRange = ['from' => $from, 'to' => $to];

        return Excel::download(
            new LinkStatsCsvExport($stats, $linkData, $dateRange),
            'link_stats_' . $link->alias . '_' . date('Ymd') . '.csv'
        );
    }

    /**
     * Export statistics data to PDF
     */
    public function exportStatsToPdf(Link $link, string $from, string $to)
    {
        $stats = $this->getDetailedStats($link->id, $from, $to);
        $linkData = $this->prepareLinkData($link);
        $dateRange = ['from' => $from, 'to' => $to];

        $pdfExport = new LinkStatsPdfExport($stats, $linkData, $dateRange);
        return $pdfExport->generate()->download('link_stats_' . $link->alias . '_' . date('Ymd') . '.pdf');
    }
}
