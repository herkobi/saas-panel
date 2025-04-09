<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LinkStatsCsvExport implements FromArray, WithHeadings, ShouldAutoSize
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
     * @return array
     */
    public function array(): array
    {
        $rows = [];

        // Günlük tıklama verileri
        foreach ($this->stats['dailyClicks'] as $date => $count) {
            $rows[] = [$date, $count];
        }

        // Boş satır
        $rows[] = ['', ''];

        // Özet istatistikler
        $rows[] = ['Özet Bilgiler', ''];
        $rows[] = ['Toplam Tıklama', $this->stats['totalClicks']];
        $rows[] = ['Benzersiz Tıklama', $this->stats['uniqueClicks']];
        $rows[] = ['Dönüşüm Oranı', $this->calculateConversionRate()];
        $rows[] = ['Tarih Aralığı', $this->dateRange['from'] . ' - ' . $this->dateRange['to']];

        // Boş satır
        $rows[] = ['', ''];

        // Tarayıcı istatistikleri
        $rows[] = ['Tarayıcı İstatistikleri', ''];
        $rows[] = ['Tarayıcı', 'Tıklama Sayısı', 'Yüzde (%)'];

        foreach ($this->stats['browserStats'] as $browser) {
            $percentage = ($this->stats['totalClicks'] > 0)
                ? round(($browser->count / $this->stats['totalClicks']) * 100, 1) . '%'
                : '0%';

            $rows[] = [$browser->value, $browser->count, $percentage];
        }

        // Boş satır
        $rows[] = ['', '', ''];

        // Platform istatistikleri
        $rows[] = ['Platform İstatistikleri', '', ''];
        $rows[] = ['Platform', 'Tıklama Sayısı', 'Yüzde (%)'];

        foreach ($this->stats['platformStats'] as $platform) {
            $percentage = ($this->stats['totalClicks'] > 0)
                ? round(($platform->count / $this->stats['totalClicks']) * 100, 1) . '%'
                : '0%';

            $rows[] = [$platform->value, $platform->count, $percentage];
        }

        // Boş satır
        $rows[] = ['', '', ''];

        // Ülke istatistikleri
        $rows[] = ['Ülke İstatistikleri', '', ''];
        $rows[] = ['Ülke', 'Tıklama Sayısı', 'Yüzde (%)'];

        foreach ($this->stats['countryStats'] as $country) {
            $percentage = ($this->stats['totalClicks'] > 0)
                ? round(($country->count / $this->stats['totalClicks']) * 100, 1) . '%'
                : '0%';

            $rows[] = [$country->value, $country->count, $percentage];
        }

        return $rows;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tarih / Başlık',
            'Tıklama Sayısı',
            'Yüzde (%)'
        ];
    }

    /**
     * Calculate conversion rate
     */
    private function calculateConversionRate(): string
    {
        if ($this->stats['totalClicks'] == 0) {
            return '0%';
        }

        $rate = ($this->stats['uniqueClicks'] / $this->stats['totalClicks']) * 100;
        return number_format($rate, 1) . '%';
    }
}
