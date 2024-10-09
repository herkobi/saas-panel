<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Services\Admin\Settings\CurrencyService;
use App\Actions\Admin\Settings\Currency\Create;
use App\Actions\Admin\Settings\Currency\Update;
use App\Actions\Admin\Settings\Currency\Delete;
use App\Http\Requests\Admin\Settings\Currency\CurrencyUpdateRequest;
use App\Http\Requests\Admin\Settings\Currency\CurrencyCreateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CurrencyController extends Controller
{
    protected $currencyService;
    protected $createCurrency;
    protected $updateCurrency;
    protected $deleteCurrency;


    public function __construct(
        CurrencyService $currencyService,
        Create $createCurrency,
        Update $updateCurrency,
        Delete $deleteCurrency
    ) {
        $this->currencyService = $currencyService;
        $this->createCurrency = $createCurrency;
        $this->updateCurrency = $updateCurrency;
        $this->deleteCurrency = $deleteCurrency;
    }

    public function index(): View
    {
        $currencies = $this->currencyService->getAllCurrencies();
        return view('admin.settings.currencies.index', [
            'currencies' => $currencies
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.currencies.create');
    }

    public function store(CurrencyCreateRequest $request): RedirectResponse
    {
        $created = $this->createCurrency->execute($request->validated());
        return $created
                ? Redirect::route('panel.settings.currencies')->with('success', 'Sayfanız başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Sayfa oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit($id): View
    {
        $currency = $this->currencyService->getCurrencyById($id);
        return view('admin.settings.currencies.edit', [
            'currency' => $currency
        ]);
    }

    public function update(CurrencyUpdateRequest $request, $id): RedirectResponse
    {
        $updated = $this->updateCurrency->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.settings.currencies')->with('success', 'Sayfanız başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Sayfa güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy($id): RedirectResponse
    {
        $deleted = $this->deleteCurrency->execute($id);
        return $deleted
                ? Redirect::route('panel.settings.currencies')->with('success', 'Sayfanız başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'Sayfa silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
