<?php

namespace App\Http\Controllers\Admin\Plans;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Plans\PlanCreateRequest;
use App\Http\Requests\Admin\Plans\PlanUpdateRequest;
use App\Models\Admin\Currency;
use App\Models\Admin\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index() : View
    {
        $plans = Plan::all();
        return view('admin.plans.index', [
            'plans' => $plans
        ]);
    }

    public function create() : View
    {
        $currencies = Currency::pluck('title', 'id')->toArray();
        $periods = [
            'Day' => __('admin/plans/plans.period_day'),
            'Week' => __('admin/plans/plans.period_week'),
            'Month' =>  __('admin/plans/plans.period_month'),
            'Year' => __('admin/plans/plans.period_year')
        ];
        return view('admin.plans.create', [
            'currencies' => $currencies,
            'periods' => $periods
        ]);
    }

    public function store(PlanCreateRequest $request) : RedirectResponse
    {

        $plan = Plan::create([
            'status' => $request->status,
            'title' => $request->title,
            'name' => Str::slug($request->title, '-'),
            'price' => $request->price,
            'currency_id' => $request->currency_id,
            'periodicity' => $request->price == 0 ? null : $request->periodicity,
            'periodicity_type' => $request->price == 0 ? null : $request->periodicity_type,
            'grace_days' => $request->price == 0 ? 0 : $request->grace_days,
        ]);

        return Redirect::route('panel.plans.plans')->with('success', __('admin/plans/plans.store.success'));
    }

    public function edit(Plan $plan): View
    {
        $currencies = Currency::pluck('title', 'id')->toArray();
        return view('admin.plans.edit', [
            'plan' => $plan,
            'currencies' => $currencies
        ]);
    }

    public function update(PlanUpdateRequest $request, Plan $plan): RedirectResponse
    {
        $plan->status = $request->status;
        $plan->title = $request->title;
        $plan->name = Str::slug($request->title, '-');
        $plan->price = $request->price;
        $plan->currency_id = $request->currency_id;
        $plan->periodicity = $request->price == 0 ? null : $request->periodicity;
        $plan->periodicity_type = $request->price == 0 ? null : $request->periodicity_type;
        $plan->grace_days = $request->price == 0 ? 0 : $request->grace_days;

        $plan->save();

        return Redirect::route('panel.plans.plans')->with('success', __('admin/plans/plans.update.success'));

    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $status = Status::PASSIVE;
        $plan->update(['status' => $status]);

        $plan->delete();
        return Redirect::route('panel.plans.plans')->with('success', __('admin/plans/plans.delete.success'));
    }

    public function deleted() : View
    {
        $plans = Plan::onlyTrashed()->get();
        return view('admin.plans.deleted', [
            'plans' => $plans
        ]);
    }

    public function restore(Plan $plan): RedirectResponse
    {
        Plan::withTrashed()->where('id', $plan->id)->restore();
        return Redirect::route('panel.plans.plan.deleted')->with('success', __('admin/plans/plans.feature.restored'));
    }

    public function forcedelete(Request $request): RedirectResponse
    {
        Plan::withTrashed()->where('id', $request->planId)->forceDelete();
        return Redirect::route('panel.plans.plan.deleted')->with('success', __('admin/plans/plans.feature.forcedelete'));
    }
}
