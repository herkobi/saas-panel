<?php

namespace App\Enums;

enum PixelType: string
{
    case ADROLL = 'adroll';
    case GOOGLE_ADS = 'google-ads';
    case BING = 'bing';
    case FACEBOOK = 'facebook';
    case GOOGLE_ANALYTICS = 'google-analytics';
    case GOOGLE_TAG_MANAGER = 'google-tag-manager';
    case LINKEDIN = 'linkedin';
    case PINTEREST = 'pinterest';
    case QUORA = 'quora';
    case X = 'x';

    public function getPolicyUrl(): string
    {
        return match($this) {
            self::ADROLL => 'https://www.nextroll.com/privacy',
            self::BING => 'https://privacy.microsoft.com/en-us/privacystatement',
            self::FACEBOOK => 'https://www.facebook.com/policies/cookies/',
            self::GOOGLE_ADS => 'https://support.google.com/google-ads/answer/2407785',
            self::GOOGLE_ANALYTICS => 'https://policies.google.com/technologies/partner-sites',
            self::GOOGLE_TAG_MANAGER => 'https://marketingplatform.google.com/about/analytics/tag-manager/use-policy/',
            self::LINKEDIN => 'https://www.linkedin.com/legal/cookie-policy',
            self::PINTEREST => 'https://policy.pinterest.com/en/cookies',
            self::QUORA => 'https://www.quora.com/about/privacy',
            self::X => 'https://help.twitter.com/en/rules-and-policies/x-cookies',
        };
    }

    public function isMarketing(): bool
    {
        return match($this) {
            self::GOOGLE_ANALYTICS => false,
            default => true,
        };
    }
}
