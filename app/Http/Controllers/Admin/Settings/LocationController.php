<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\CurrencyCreateRequest;
use App\Http\Requests\Admin\Settings\CurrencyUpdateRequest;
use App\Models\Admin\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LocationController extends Controller
{

    public function index(): View
    {
        $locations = Currency::all();
        return view('admin.settings.locations.index', [
            'locations' => $locations
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.locations.create');
    }

    public function store(CurrencyCreateRequest $request): RedirectResponse
    {
        $currency = Currency::create([
            'status' => $request->status,
            'title' => $request->title,
            'symbol' => $request->symbol,
            'code' => $request->code
        ]);

        return Redirect::route('panel.settings.locations')->with('success', __('admin/settings/locations.store.success'));
    }

    public function edit(Currency $currency): View
    {
        return view('admin.settings.locations.edit', [
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

        return Redirect::route('panel.settings.locations')->with('success', __('admin/settings/locations.update.success'));

    }
}
