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
        return view('admin.plans.features.index', [
            'features' => $features
        ]);
    }

    public function create() : View
    {
        return view('admin.plans.features.create');
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
            'periodicity' => $request->periodicity,
            'periodicity_type' => $request->periodicity_type,
        ]);

        return Redirect::route('panel.plans.features')->with('success', __('admin/plans/plan.features.store.success'));

    }

    public function edit(Feature $feature): View
    {
        if($feature->trashed()) {
            return Redirect::back()->with('error', __('admin/plans/features.doesnt.edit.featured.item'));
        }

        return view('admin.plans.features.edit', [
            'feature' => $feature
        ]);
    }

    public function update(FeatureUpdateRequest $request, Feature $feature): RedirectResponse
    {
        $feature->status = $request->status;
        $feature->title = $request->title;
        $feature->name = Str::slug($request->title, '-');
        $feature->consumable = $request->consumable;
        $feature->quota = $request->quota;
        $feature->postpaid = $request->postpaid;
        $feature->periodicity = $request->periodicity;
        $feature->periodicity_type = $request->periodicity_type;

        $feature->save();

        return Redirect::route('panel.plans.features')->with('success', __('admin/settings/features.update.success'));
    }

    public function destroy(Feature $feature): RedirectResponse
    {
        $status = Status::PASSIVE;
        $feature->update(['status' => $status]);

        $feature->delete();
        return Redirect::route('panel.plans.features')->with('success', __('admin/plans/features.feaure.delete.success.text'));
    }

    public function deleted() : View
    {
        $features = Feature::onlyTrashed()->get();
        return view('admin.plans.features.deleted', [
            'features' => $features
        ]);
    }

    public function restore(Feature $feature): RedirectResponse
    {
        Feature::withTrashed()->where('id', $feature->id)->restore();
        return Redirect::route('panel.plans.features.deleted')->with('success', __('admin/plans/features.feature.restored'));
    }

    public function forcedelete(Request $request): RedirectResponse
    {
        Feature::withTrashed()->where('id', $request->featureId)->forceDelete();
        return Redirect::route('panel.plans.features.deleted')->with('success', __('admin/plans/features.feature.restored'));
    }
}
