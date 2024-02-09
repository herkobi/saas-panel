<?php

namespace App\Http\Controllers\Admin\Features;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Features\FeatureCreateRequest;
use App\Http\Requests\Admin\Features\FeatureUpdateRequest;
use App\Models\Admin\Feature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str;

class FeatureController extends Controller
{
    public function index() : View
    {
        $features = Feature::all();
        return view('admin.features.index', [
            'features' => $features
        ]);
    }

    public function create() : View
    {
        return view('admin.features.create');
    }

    public function store(FeatureCreateRequest $request) : RedirectResponse
    {
        $feature = Feature::create([
            'status' => $request->status,
            'title' => $request->title,
            'name' => Str::slug($request->title, '-'),
            'consumable' => $request->consumable,
            'quota' => $request->quota,
            'postpaid' => $request->postpaid,
            'periodicity' => $request->consumable == 0 ? null : $request->periodicity,
            'periodicity_type' => $request->consumable == 0 ? null : $request->periodicity_type,
        ]);

        return Redirect::route('panel.plans.features')->with('success', __('admin/plans/features.store.success'));

    }

    public function edit(Feature $feature): View
    {
        if($feature->trashed()) {
            return Redirect::back()->with('error', __('admin/plans/features.doesnt.edit'));
        }

        return view('admin.features.edit', [
            'feature' => $feature
        ]);
    }

    public function update(FeatureUpdateRequest $request, Feature $feature): RedirectResponse
    {
        $feature->update([
            'status' => $request->status,
            'title' => $request->title,
            'name' => Str::slug($request->title, '-'),
            'consumable' => $request->consumable,
            'quota' => $request->quota,
            'postpaid' => $request->postpaid,
            'periodicity' => $request->consumable == 0 ? null : $request->periodicity,
            'periodicity_type' => $request->consumable == 0 ? null : $request->periodicity_type,
        ]);

        return Redirect::route('panel.plans.features')->with('success', __('admin/features/features.update.success'));
    }

    public function destroy(Feature $feature): RedirectResponse
    {
        $status = Status::PASSIVE;
        $feature->update(['status' => $status]);

        $feature->delete();
        return Redirect::route('panel.plans.features')->with('success', __('admin/features/features.delete.success'));
    }

    public function deleted() : View
    {
        $features = Feature::onlyTrashed()->get();
        return view('admin.features.deleted', [
            'features' => $features
        ]);
    }

    public function restore(Feature $feature): RedirectResponse
    {
        Feature::withTrashed()->where('id', $feature->id)->restore();
        return Redirect::route('panel.plans.features.deleted')->with('success', __('admin/features/features.feature.restored'));
    }

    public function forcedelete(Request $request): RedirectResponse
    {
        Feature::withTrashed()->where('id', $request->featureId)->forceDelete();
        return Redirect::route('panel.plans.features.deleted')->with('success', __('admin/features/features.feature.forcedelete'));
    }
}
