<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LinkStatsExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnFormatting
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
        $rows[] = ['Tarayıcı İstatistikleri', '', ''];

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
     * @return string
     */
    public function title(): string
    {
        return 'Link İstatistikleri - ' . $this->link['alias'];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Başlık satırı
            1 => ['font' => ['bold' => true]],

            // Özet başlık
            count($this->stats['dailyClicks']) + 3 => ['font' => ['bold' => true]],

            // Tarayıcı başlık
            count($this->stats['dailyClicks']) + 9 => ['font' => ['bold' => true]],

            // Platform başlık
            count($this->stats['dailyClicks']) + 11 + count($this->stats['browserStats']) => ['font' => ['bold' => true]],

            // Ülke başlık
            count($this->stats['dailyClicks']) + 13 + count($this->stats['browserStats']) + count($this->stats['platformStats']) => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_PERCENTAGE_00,
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
