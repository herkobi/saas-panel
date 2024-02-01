<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Location\StateCreateRequest;
use App\Http\Requests\Admin\Settings\Location\StateUpdateRequest;
use App\Models\Admin\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str;

class StateController extends Controller
{

    public function index(): View
    {
        $states = State::all();
        return view('admin.settings.locations.state.index', [
            'states' => $states
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.locations.state.create');
    }

    public function store(StateCreateRequest $request): RedirectResponse
    {
        $location = State::create([
            'status' => $request->status,
            'state' => $request->state,
            'slug' => Str::slug($request->state, '-'),
            'country_id' => $request->country_id,
        ]);

        return Redirect::route('panel.settings.locations.states')->with('success', __('admin/settings/locations.state.store.success'));
    }

    public function edit(State $state): View
    {
        return view('admin.settings.locations.state.edit', [
            'state' => $state
        ]);
    }

    public function update(StateUpdateRequest $request, State $state): RedirectResponse
    {

        $state->update([
            'status' => $request->status,
            'state' => $request->state,
            'slug' => Str::slug($request->state, '-'),
            'country_id' => $request->country_id,
            'code' => $request->code
        ]);

        return Redirect::route('panel.settings.locations.states')->with('success', __('admin/settings/locations.state.update.success'));

    }

    public function destroy(State $state): RedirectResponse
    {
        $state->delete();
        return Redirect::route('panel.settings.locations.states')->with('success', __('admin/settings/locations.state.destroy.success'));
    }
}
