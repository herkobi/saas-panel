<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Actions\Admin\Settings\Paytr\Update;
use App\Facades\Setting;
use App\Http\Requests\Admin\Settings\Paytr\PaytrUpdateRequest;
use App\Services\Admin\Settings\PaytrService;
use App\Services\Admin\Tools\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PaytrController extends Controller
{
    protected $paytrService;
    protected $currencyService;
    protected $updatePaytr;

    public function __construct(
        PaytrService $paytrService,
        CurrencyService $currencyService,
        Update $updatePaytr,
    ) {
        $this->paytrService = $paytrService;
        $this->currencyService = $currencyService;
        $this->updatePaytr = $updatePaytr;
    }

    public function edit(): View
    {
        $paytrSettings = $this->paytrService->getPaytrData();
        $paytr = $paytrSettings ? json_decode($paytrSettings) : null;
        $currencies = $this->currencyService->getActiveCurrencies();

        return view('admin.settings.payments.paytr.edit', [
            'paytr' => $paytr,
            'currencies' => $currencies
        ]);
    }

    public function update(PaytrUpdateRequest $request): RedirectResponse
    {
        $updated = $this->updatePaytr->execute($request->validated());
        return $updated
                ? Redirect::route('panel.settings.payments.paytr')->with('success', 'Paytr hesap bilginiz başarılı bir şekilde güncellendi.')
                : Redirect::paytrk()->with('error', 'Paytr hesap bilginiz güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
