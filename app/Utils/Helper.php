<?php

namespace App\Utils;

use App\Models\Currency;
use DateTimeZone;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Helper
{
    /**
     * Timezones
     *
     * List of timezones
     */
    static public function getTimeZoneList()
    {
        return Cache::rememberForever('timezones_list_collection', function () {
            $timestamp = time();
            foreach (timezone_identifiers_list(DateTimeZone::ALL) as $key => $value) {
                date_default_timezone_set($value);
                $timezone[$value] = $value . ' (UTC ' . date('P', $timestamp) . ')';
            }
            return collect($timezone)->sortKeys();
        });
    }

    /**
     * Date Formats
     *
     * Which will try to obtain the timezone defined by the user,
     * if it exists, and otherwise it will return the application’s default timezone.
     */
    public static function dateformats(): array
    {
        return [
            'j F Y',
            'F j, Y',
            'Y-m-d',
            'm/d/Y',
            'd/m/Y',
        ];
    }

    /**
     * Time Formats
     *
     * List of time formats
     */
    public static function timeformats(): array
    {
        return [
            'g:i a',
            'g:i:s a',
            'H:i',
        ];
    }

    /**
     * Format a price according to the currency settings.
     *
     * @param float $amount
     * @param string $currencyCode
     * @return string
     */
    public static function formatPrice($amount, $currencyCode)
    {
        // Retrieve currency settings from the database
        $currency = Currency::where('code', $currencyCode)->firstOrFail();

        // Extract currency settings
        $symbol = $currency->symbol;
        $symbolLocation = $currency->symbol_location;
        $thousandSep = $currency->thousand_sep ?? '.';  // Default to '.' if not set
        $decimalSep = $currency->decimal_sep ?? ',';    // Default to ',' if not set
        $decimalNumber = $currency->decimal_number ?? 2;  // Default to 2 if not set

        // Ensure the amount is treated as a float
        $amount = floatval($amount);

        // Format the amount
        $formattedAmount = number_format($amount, $decimalNumber, $decimalSep, $thousandSep);

        // Place the symbol according to the symbol_location setting
        switch ($symbolLocation) {
            case 'left':
                return $symbol . $formattedAmount;
            case 'left_space':
                return $symbol . ' ' . $formattedAmount;
            case 'right':
                return $formattedAmount . $symbol;
            case 'right_space':
                return $formattedAmount . ' ' . $symbol;
            default:
                return $formattedAmount;
        }
    }

    /**
     * Public klasörü altındaki dosyalara erişim sağlar.
     *
     * @param string|null $path Dosya yolu (örn: 'brands/image.png')
     * @param string|null $default Varsayılan dosya yolu
     * @return string Dosyanın tam URL'i
     *
     * Kullanım örnekleri:
     * get_image('brands/image.png')
     * get_image('products/image.jpg', 'defaults/no-product.jpg')
     */
    static public function getImage(?string $path, ?string $default = null): string
    {
        if (empty($path)) {
            return asset($default ?? 'defaults/no-image.png');
        }

        // Cache key oluştur
        $cacheKey = 'image_path_' . md5($path);

        return Cache::remember($cacheKey, now()->addDay(), function() use ($path, $default) {
            // Storage'dan gelen yolu kontrol et
            if (Storage::disk('public')->exists(str_replace('storage/', '', $path))) {
                return asset($path);
            }

            // Public klasöründe direkt kontrol et
            if (file_exists(public_path($path))) {
                return asset($path);
            }

            return asset($default ?? 'defaults/no-image.png');
        });
    }

}
