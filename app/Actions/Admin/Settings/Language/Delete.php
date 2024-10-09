<?php

namespace App\Actions\Admin\Settings\Language;

use App\Services\Admin\Settings\LanguageService;
use App\Events\Admin\Settings\Language\Delete as Event;
use App\Models\Language;
use App\Traits\AuthUser;

class Delete
{
    use AuthUser;

    protected $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
        $this->initializeAuthUser();
    }

    public function execute(string $id): Language
    {
        $language = $this->languageService->getLanguageById($id);
        $this->languageService->deleteLanguage($id);
        event(new Event($language, $this->user));
        return $language;
    }
}
