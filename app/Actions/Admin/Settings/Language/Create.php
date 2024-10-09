<?php

namespace App\Actions\Admin\Settings\Language;

use App\Models\Language;
use App\Services\Admin\Settings\LanguageService;
use App\Events\Admin\Settings\Language\Create as Event;
use App\Traits\AuthUser;

class Create
{
    use AuthUser;

    protected $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): Language
    {
        $language = $this->languageService->createLanguage($data);
        event(new Event($language, $this->user));
        return $language;
    }
}
