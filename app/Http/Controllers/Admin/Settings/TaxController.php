<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Tax\TaxCreateRequest;
use App\Http\Requests\Admin\Settings\Tax\TaxUpdateRequest;
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
        return view('admin.settings.tax.index', [
            'taxes' => $taxes
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.tax.create');
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

        return Redirect::route('panel.settings.taxes')->with('success', __('admin/settings/tax.store.success'));
    }

    public function edit(Tax $tax): View
    {
        return view('admin.settings.tax.edit', [
            'tax' => $tax
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

        return Redirect::route('panel.settings.taxes')->with('success', __('admin/settings/tax.update.success'));
    }

    public function destroy(Tax $tax): RedirectResponse
    {
        $tax->delete();
        return Redirect::route('panel.settings.taxes')->with('success', __('admin/settings/tax.destroy.success'));
    }
}
