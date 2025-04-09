<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Link;
use App\Models\Space;
use App\Models\Pixel;
use App\Services\Tenant\DashboardService;
use App\Services\Tenant\StatService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(protected StatService $statService, protected DashboardService $dashboardService)
    {
    }

    public function index(): Response
    {
        // Son 30 günlük istatistikler için tarih aralığı
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(29);

        // Dashboard'da gösterilecek özet veriler
        $totalLinks = Link::count();
        $totalSpaces = Space::count();
        $totalPixels = Pixel::count();
        $totalCampaigns = Campaign::count();

        // Son eklenen linkler (5 adet)
        $recentLinks = $this->dashboardService->getRecentLinks(5);

        // En çok tıklanan linkler (5 adet)
        $popularLinks = $this->dashboardService->getPopularLinks(5);

        // Günlük tıklama istatistikleri
        $clicksData = $this->dashboardService->getDailyClicksStats(
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        );

        // Yaklaşan etkinlikleri al
        $upcomingEvents = $this->dashboardService->getUpcomingEvents(3);

        // Hızlı istatistik kartları için verileri al
        $quickStats = $this->dashboardService->getQuickStats();

        // Kullanıcının adını göster
        $userName = Auth::user()->name;

        return Inertia::render('tenant/Dashboard', [
            'stats' => [
                'totalLinks' => $totalLinks,
                'totalSpaces' => $totalSpaces,
                'totalPixels' => $totalPixels,
                'totalCampaigns' => $totalCampaigns,
            ],
            'recentLinks' => $recentLinks,
            'popularLinks' => $popularLinks,
            'clicksData' => $clicksData,
            'dateRange' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
            'name' => $userName,
            'upcomingEvents' => $upcomingEvents,
            'quickStats' => $quickStats,
        ]);
    }
}
