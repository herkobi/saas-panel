<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Location\LocationCreateRequest;
use App\Http\Requests\Admin\Settings\Location\LocationUpdateRequest;
use App\Models\Admin\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LocationController extends Controller
{

    public function index(): View
    {
        $locations = Location::all();
        return view('admin.settings.locations.index', [
            'locations' => $locations
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.locations.create');
    }

    public function store(LocationCreateRequest $request): RedirectResponse
    {
        $location = Location::create([
            'status' => $request->status,
            'title' => $request->title,
            'symbol' => $request->symbol,
            'code' => $request->code
        ]);

        return Redirect::route('panel.settings.locations')->with('success', __('admin/settings/locations.store.success'));
    }

    public function edit(Location $location): View
    {
        return view('admin.settings.locations.edit', [
            'location' => $location
        ]);
    }

    public function update(LocationUpdateRequest $request, Location $location): RedirectResponse
    {

        $location->update([
            'status' => $request->status,
            'parent_id' => $request->parent_id,
            'title' => $request->title,
        ]);

        return Redirect::route('panel.settings.locations')->with('success', __('admin/settings/locations.update.success'));

    }
}
