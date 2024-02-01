<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Tax\TaxCreateRequest;
use App\Http\Requests\Admin\Settings\Tax\TaxUpdateRequest;
use App\Models\Admin\Country;
use App\Models\Admin\Tax;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TaxController extends Controller
{
    public function index(): View
    {
        $taxes = Tax::all();
        return view('admin.settings.taxes.index', [
            'taxes' => $taxes
        ]);
    }

    public function create(): View
    {
        $countries = Country::pluck('country', 'id')->toArray();
        return view('admin.settings.taxes.create', [
            'countries' => $countries
        ]);
    }

    public function store(TaxCreateRequest $request): RedirectResponse
    {
        $tax = Tax::create([
            'status' => $request->status,
            'title' => $request->title,
            'desc' => $request->desc,
            'value' => $request->value,
            'country_id' => $request->country_id
        ]);

        return Redirect::route('panel.settings.taxes')->with('success', __('admin/settings/taxes.store.success'));
    }

    public function edit(Tax $tax): View
    {
        $countries = Country::pluck('country', 'id')->toArray();
        return view('admin.settings.taxes.edit', [
            'tax' => $tax,
            'countries' => $countries
        ]);
    }

    public function update(TaxUpdateRequest $request, Tax $tax): RedirectResponse
    {

        $tax->update([
            'status' => $request->status,
            'title' => $request->title,
            'desc' => $request->desc,
            'value' => $request->value,
            'country_id' => $request->country_id
        ]);

        return Redirect::route('panel.settings.taxes')->with('success', __('admin/settings/taxes.update.success'));
    }

    public function destroy(Tax $tax): RedirectResponse
    {
        $tax->delete();
        return Redirect::route('panel.settings.taxes')->with('success', __('admin/settings/taxes.destroy.success'));
    }
}
