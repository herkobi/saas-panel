<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Location\CountryCreateRequest;
use App\Http\Requests\Admin\Settings\Location\CountryUpdateRequest;
use App\Models\Admin\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CountryController extends Controller
{

    public function index(): View
    {
        $countries = Country::all();
        return view('admin.settings.locations.country.index', [
            'countries' => $countries
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.locations.country.create');
    }

    public function store(CountryCreateRequest $request): RedirectResponse
    {
        $location = Country::create([
            'status' => $request->status,
            'country' => $request->country,
            'slug' => Str::slug($request->country, '-'),
            'code' => $request->code
        ]);

        return Redirect::route('panel.settings.locations.countries')->with('success', __('admin/settings/locations/country.store.success'));
    }

    public function edit(Country $country): View
    {
        return view('admin.settings.locations.country.edit', [
            'country' => $country
        ]);
    }

    public function update(CountryUpdateRequest $request, Country $country): RedirectResponse
    {

        $country->update([
            'status' => $request->status,
            'country' => $request->country,
            'slug' => Str::slug($request->country, '-'),
            'code' => $request->code
        ]);

        return Redirect::route('panel.settings.locations.countries')->with('success', __('admin/settings/locations/country.update.success'));

    }

    public function destroy(Country $country): RedirectResponse
    {
        if($country->states()->count() == 0)
        {
            $country->delete();
            return Redirect::route('panel.settings.locations.countries')->with('success', __('admin/settings/locations/country.destroy.success'));
        }

        return Redirect::route('panel.settings.locations.countries')->with('error', __('admin/settings/locations/country.destroy.success'));

    }
}
