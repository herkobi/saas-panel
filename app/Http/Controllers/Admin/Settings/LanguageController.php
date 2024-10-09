<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Services\Admin\Settings\LanguageService;
use App\Actions\Admin\Settings\Language\Create;
use App\Actions\Admin\Settings\Language\Update;
use App\Actions\Admin\Settings\Language\Delete;
use App\Http\Requests\Admin\Settings\Language\LanguageUpdateRequest;
use App\Http\Requests\Admin\Settings\Language\LanguageCreateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LanguageController extends Controller
{
    protected $languageService;
    protected $createLanguage;
    protected $updateLanguage;
    protected $deleteLanguage;


    public function __construct(
        LanguageService $languageService,
        Create $createLanguage,
        Update $updateLanguage,
        Delete $deleteLanguage
    ) {
        $this->languageService = $languageService;
        $this->createLanguage = $createLanguage;
        $this->updateLanguage = $updateLanguage;
        $this->deleteLanguage = $deleteLanguage;
    }

    public function index(): View
    {
        $languages = $this->languageService->getAllLanguages();
        return view('admin.settings.languages.index', [
            'languages' => $languages
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.languages.create');
    }

    public function store(LanguageCreateRequest $request): RedirectResponse
    {
        $created = $this->createLanguage->execute($request->validated());
        return $created
                ? Redirect::route('panel.settings.languages')->with('success', 'Sayfanız başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Sayfa oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit($id): View
    {
        $language = $this->languageService->getLanguageById($id);
        return view('admin.settings.languages.edit', [
            'language' => $language
        ]);
    }

    public function update(LanguageUpdateRequest $request, $id): RedirectResponse
    {
        $updated = $this->updateLanguage->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.settings.languages')->with('success', 'Sayfanız başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Sayfa güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy($id): RedirectResponse
    {
        $deleted = $this->deleteLanguage->execute($id);
        return $deleted
                ? Redirect::route('panel.settings.languages')->with('success', 'Sayfanız başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'Sayfa silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
