<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Actions\Admin\Settings\Settings as SettingsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Settings\GeneralSettingsUpdateRequest;
use App\Http\Requests\Admin\Settings\Settings\SystemSettingsUpdateRequest;
use App\Services\Admin\Tools\CountryService;
use App\Services\Admin\Tools\CurrencyService;
use App\Services\Admin\Tools\LanguageService;
use App\Services\Admin\Tools\TaxService;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SettingsController extends Controller
{
    protected $settingService;
    protected $languageService;
    protected $currencyService;
    protected $countryService;
    protected $taxService;

    protected $updateSettingsAction;

    public function __construct(
        SettingService $settingService,
        LanguageService $languageService,
        CurrencyService $currencyService,
        CountryService $countryService,
        TaxService $taxService,
        SettingsAction $updateSettingsAction,
    ) {
        $this->settingService = $settingService;
        $this->languageService = $languageService;
        $this->currencyService = $currencyService;
        $this->countryService = $countryService;
        $this->taxService = $taxService;
        $this->updateSettingsAction = $updateSettingsAction;
    }

    public function index(): View
    {
        $settings = $this->settingService->all();
        return view('admin.settings.system.index', [
            'settings' => $settings
        ]);
    }

    public function system(): View
    {
        $settings = $this->settingService->all();
        $languages = $this->languageService->getActiveLanguages();
        $currencies = $this->currencyService->getActiveCurrencies();
        $countries = $this->countryService->getActiveCountries();
        $taxes = $this->taxService->getActiveTaxes();
        return view('admin.settings.system.system', [
            'settings' => $settings,
            'languages' => $languages,
            'currencies' => $currencies,
            'countries' => $countries,
            'taxes' => $taxes
        ]);
    }

    public function updateGeneral(GeneralSettingsUpdateRequest $request): RedirectResponse
    {
        $updated = $this->updateSettingsAction->execute($request->validated());
        return $updated
            ? Redirect::route('panel.settings.general')->with('success', 'Uygulama bilgileri başarılı bir şekilde güncellendi.')
            : Redirect::route('panel.settings.general')->with('error', 'Uygulama bilgileri güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function updateSystem(SystemSettingsUpdateRequest $request): RedirectResponse
    {
        $updated = $this->updateSettingsAction->execute($request->validated());
        return $updated
            ? Redirect::route('panel.settings.system')->with('success', 'Sistem bilgileri başarılı bir şekilde güncellendi.')
            : Redirect::route('panel.settings.system')->with('error', 'Sistem bilgileri güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
