<?php

namespace App\Http\Controllers\Admin\Tools;

use App\Http\Controllers\Controller;
use App\Services\Admin\Tools\CurrencyService;
use App\Actions\Admin\Tools\Currency\Create;
use App\Actions\Admin\Tools\Currency\Update;
use App\Actions\Admin\Tools\Currency\Delete;
use App\Http\Requests\Admin\Tools\Currency\CurrencyUpdateRequest;
use App\Http\Requests\Admin\Tools\Currency\CurrencyCreateRequest;
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
        return view('admin.tools.config.currencies.index', [
            'currencies' => $currencies
        ]);
    }

    public function create(): View
    {
        return view('admin.tools.config.currencies.create');
    }

    public function store(CurrencyCreateRequest $request): RedirectResponse
    {
        $created = $this->createCurrency->execute($request->validated());
        return $created
                ? Redirect::route('panel.tools.config.currencies')->with('success', 'Para birimi başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Para birimi oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit($id): View
    {
        $currency = $this->currencyService->getCurrencyById($id);
        return view('admin.tools.config.currencies.edit', [
            'currency' => $currency
        ]);
    }

    public function update(CurrencyUpdateRequest $request, $id): RedirectResponse
    {
        $updated = $this->updateCurrency->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.tools.config.currencies')->with('success', 'Para birimi başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Para birimi güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy($id): RedirectResponse
    {
        $deleted = $this->deleteCurrency->execute($id);
        return $deleted
                ? Redirect::route('panel.tools.config.currencies')->with('success', 'Para birimi başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'Para birimi silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
