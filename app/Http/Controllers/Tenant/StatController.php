<?php

namespace App\Http\Controllers\Tenant;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Services\Tenant\LinkService;
use App\Services\Tenant\StatService;
use App\Http\Controllers\Controller;

class StatController extends Controller
{
    public function __construct(protected LinkService $linkService, protected StatService $statService)
    {
    }

    /**
     * Display detailed statistics for a link.
     */
    public function detail(Request $request, int $id): Response
    {
        $link = $this->linkService->getLinkById($id);

        if (!$link) {
            abort(404);
        }

        // İstatistikleri görüntüleme yetkisi kontrolü
        $this->authorize('viewStats', $link);

        // Tarih aralığını al (varsayılan olarak son 30 gün)
        $end = Carbon::now()->endOfDay();
        $start = Carbon::now()->subDays(29)->startOfDay(); // 30 gün için

        // Filtreler
        if ($request->has('start_date') && $request->has('end_date')) {
            $start = Carbon::parse($request->input('start_date'))->startOfDay();
            $end = Carbon::parse($request->input('end_date'))->endOfDay();
        }

        // Detaylı istatistikleri al
        $stats = $this->statService->getDetailedStats(
            $link->id,
            $start->format('Y-m-d'),
            $end->format('Y-m-d')
        );

        return Inertia::render('tenant/links/Stats', [
            'link' => [
                'id' => $link->id,
                'url' => $link->url,
                'alias' => $link->alias,
                'title' => $link->title,
                'description' => $link->description,
                'clicks' => $link->clicks,
            ],
            'stats' => $stats,
            'dateRange' => [
                'startDate' => $start->format('Y-m-d'),
                'endDate' => $end->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Export statistics data.
     */
    public function export(Request $request, int $id)
    {
        $link = $this->linkService->getLinkById($id);

        if (!$link) {
            abort(404);
        }

        // İstatistikleri dışa aktarma yetkisi kontrolü
        $this->authorize('viewStats', $link);

        $start = Carbon::parse($request->input('start_date', Carbon::now()->subDays(29)->format('Y-m-d')))->startOfDay();
        $end = Carbon::parse($request->input('end_date', Carbon::now()->format('Y-m-d')))->endOfDay();

        // Dışa aktarma formatını kontrol et
        $format = $request->input('format', 'excel');

        switch ($format) {
            case 'csv':
                return $this->statService->exportStatsToCsv(
                    $link,
                    $start->format('Y-m-d'),
                    $end->format('Y-m-d')
                );

            case 'pdf':
                return $this->statService->exportStatsToPdf(
                    $link,
                    $start->format('Y-m-d'),
                    $end->format('Y-m-d')
                );

            case 'excel':
            default:
                return $this->statService->exportStatsToExcel(
                    $link,
                    $start->format('Y-m-d'),
                    $end->format('Y-m-d')
                );
        }
    }
}
