<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Currency\CurrencyCreateRequest;
use App\Http\Requests\Admin\Settings\Currency\CurrencyUpdateRequest;
use App\Models\Admin\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CurrencyController extends Controller
{

    public function index(): View
    {
        $currencies = Currency::all();
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
        $currency = Currency::create([
            'status' => $request->status,
            'title' => $request->title,
            'symbol' => $request->symbol,
            'code' => $request->code
        ]);

        return Redirect::route('panel.settings.currencies')->with('success', __('admin/settings/currencies.store.success'));
    }

    public function edit(Currency $currency): View
    {
        return view('admin.settings.currencies.edit', [
            'currency' => $currency
        ]);
    }

    public function update(CurrencyUpdateRequest $request, Currency $currency): RedirectResponse
    {

        $currency->status = $request->status;
        $currency->title = $request->title;
        $currency->symbol = $request->symbol;
        $currency->code = $request->code;
        $currency->save();

        return Redirect::route('panel.settings.currencies')->with('success', __('admin/settings/currencies.update.success'));

    }

    public function destroy(Currency $currency): RedirectResponse
    {

        $gatewaysCount = $currency->gateways()->count();
        if($gatewaysCount == 0 )
        {
            $currency->delete();
            return Redirect::route('panel.settings.currencies')->with('success', __('admin/settings/currencies.destroy.success'));
        }

        return Redirect::back()->with('error', __('admin/settings/currencies.destroy.error'));
    }
}
