<?php

namespace App\Enums;

enum StatType: string
{
    case BROWSER = 'browser';
    case PLATFORM = 'platform';
    case DEVICE = 'device';
    case CLICKS = 'clicks';
    case COUNTRY = 'country';
    case CITY = 'city';
    case REFERRER = 'referrer';
    case LANGUAGE = 'language';
    case CLICKS_HOUR = 'clicks_hour';
}
