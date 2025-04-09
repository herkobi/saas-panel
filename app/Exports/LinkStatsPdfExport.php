<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class LinkStatsPdfExport
{
    protected $stats;
    protected $link;
    protected $dateRange;

    public function __construct(array $stats, array $link, array $dateRange)
    {
        $this->stats = $stats;
        $this->link = $link;
        $this->dateRange = $dateRange;
    }

    /**
     * Generate PDF report
     *
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generate()
    {
        // Dönüşüm oranını hesapla
        $conversionRate = '0%';
        if ($this->stats['totalClicks'] > 0) {
            $rate = ($this->stats['uniqueClicks'] / $this->stats['totalClicks']) * 100;
            $conversionRate = number_format($rate, 1) . '%';
        }

        // Tarayıcı yüzdeleri
        $browserStats = collect($this->stats['browserStats'])->map(function($browser) {
            $percentage = ($this->stats['totalClicks'] > 0)
                ? round(($browser->count / $this->stats['totalClicks']) * 100, 1)
                : 0;

            return [
                'value' => $browser->value,
                'count' => $browser->count,
                'percentage' => $percentage
            ];
        });

        // Platform yüzdeleri
        $platformStats = collect($this->stats['platformStats'])->map(function($platform) {
            $percentage = ($this->stats['totalClicks'] > 0)
                ? round(($platform->count / $this->stats['totalClicks']) * 100, 1)
                : 0;

            return [
                'value' => $platform->value,
                'count' => $platform->count,
                'percentage' => $percentage
            ];
        });

        // Ülke yüzdeleri
        $countryStats = collect($this->stats['countryStats'])->map(function($country) {
            $percentage = ($this->stats['totalClicks'] > 0)
                ? round(($country->count / $this->stats['totalClicks']) * 100, 1)
                : 0;

            return [
                'value' => $country->value,
                'count' => $country->count,
                'percentage' => $percentage
            ];
        });

        // View'a geçirilecek veriler
        $data = [
            'link' => $this->link,
            'dailyClicks' => $this->stats['dailyClicks'],
            'totalClicks' => $this->stats['totalClicks'],
            'uniqueClicks' => $this->stats['uniqueClicks'],
            'conversionRate' => $conversionRate,
            'dateRange' => $this->dateRange,
            'browserStats' => $browserStats,
            'platformStats' => $platformStats,
            'countryStats' => $countryStats
        ];

        // PDF oluştur
        $pdf = PDF::loadView('exports.link-stats-pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }
}
