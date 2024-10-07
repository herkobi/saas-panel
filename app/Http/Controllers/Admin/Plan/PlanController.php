<?php

namespace App\Http\Controllers\Admin\Plan;

use App\Actions\Admin\Plan\Create;
use App\Actions\Admin\Plan\Delete;
use App\Actions\Admin\Plan\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Plan\PlanCreateRequest;
use App\Http\Requests\Admin\Plan\PlanUpdateRequest;
use App\Services\Admin\Feature\FeatureService;
use App\Services\Admin\Plan\PlanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PlanController extends Controller
{
    protected $planService;
    protected $featuresService;
    protected $createPlan;
    protected $updatePlan;
    protected $deletePlan;

    public function __construct(
        PlanService $planService,
        FeatureService $featuresService,
        Create $createPlan,
        Update $updatePlan,
        Delete $deletePlan
    ) {
        $this->planService = $planService;
        $this->featuresService = $featuresService;
        $this->createPlan = $createPlan;
        $this->updatePlan = $updatePlan;
        $this->deletePlan = $deletePlan;

    }

    public function index(): View
    {
        $plans = $this->planService->getAllPlans();
        return view('admin.plans.index', [
            'plans' => $plans
        ]);
    }

    public function create(): View
    {
        $features = $this->featuresService->getAllFeatures();
        return view('admin.plans.create', [
            'features' => $features
        ]);
    }

    public function store(PlanCreateRequest $request): RedirectResponse
    {
        $created = $this->createPlan->execute($request->validated());
        return $created
                ? Redirect::route('panel.plans')->with('success', 'Plan başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Plan oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit($id): View
    {
        $plan = $this->planService->getPlanById($id);
        $features = $this->featuresService->getAllFeatures();
        return view('admin.plans.edit', [
            'plan' => $plan,
            'features' => $features
        ]);
    }

    public function update(PlanUpdateRequest $request, $id): RedirectResponse
    {
        $updated = $this->updatePlan->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.plans')->with('success', 'Plan başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Plan güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy($id): RedirectResponse
    {
        $deleted = $this->deletePlan->execute($id);
        return $deleted
                ? Redirect::route('panel.plans')->with('success', 'Plan başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'Plan silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
