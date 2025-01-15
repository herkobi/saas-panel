<?php

namespace App\Http\Controllers\Admin\Plan;

use App\Actions\Admin\Plan\Create;
use App\Actions\Admin\Plan\Delete;
use App\Actions\Admin\Plan\Restore;
use App\Actions\Admin\Plan\Force;
use App\Actions\Admin\Plan\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Plan\PlanCreateRequest;
use App\Http\Requests\Admin\Plan\PlanUpdateRequest;
use App\Services\Admin\Feature\FeatureService;
use App\Services\Admin\Plan\PlanService;
use App\Services\Admin\Tools\CurrencyService;
use App\Services\Admin\TenantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;

class PlanController extends Controller
{
    protected $planService;
    protected $featuresService;
    protected $currencyService;
    protected $tenantService;
    protected $createPlan;
    protected $updatePlan;
    protected $deletePlan;
    protected $restorePlan;
    protected $forcePlan;

    public function __construct(
        PlanService $planService,
        FeatureService $featuresService,
        CurrencyService $currencyService,
        TenantService $tenantService,
        Create $createPlan,
        Update $updatePlan,
        Delete $deletePlan,
        Restore $restorePlan,
        Force $forcePlan,
    ) {
        $this->planService = $planService;
        $this->featuresService = $featuresService;
        $this->currencyService = $currencyService;
        $this->tenantService = $tenantService;
        $this->createPlan = $createPlan;
        $this->updatePlan = $updatePlan;
        $this->deletePlan = $deletePlan;
        $this->restorePlan = $restorePlan;
        $this->forcePlan = $forcePlan;
    }

    public function index(): View
    {
        $plans = $this->planService->getMainPlans();
        $tenants = $this->planService->getTenantPlans();
        return view('admin.plans.index', [
            'plans' => $plans,
            'tenants' => $tenants
        ]);
    }

    public function create(): View
    {
        $features = $this->featuresService->getFeaturesForPlans();
        $currencies = $this->currencyService->getActiveCurrencies();
        $tenants = $this->tenantService->getActiveTenants();
        $periodicityTypes = $this->getPeriodicityTypes();
        return view('admin.plans.create', [
            'features' => $features,
            'currencies' => $currencies,
            'tenants' => $tenants,
            'periodicityTypes' => $periodicityTypes
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
        $currencies = $this->currencyService->getActiveCurrencies();
        $tenants = $this->tenantService->getActiveTenants();
        $periodicityTypes = $this->getPeriodicityTypes();
        return view('admin.plans.edit', [
            'plan' => $plan,
            'features' => $features,
            'currencies' => $currencies,
            'tenants' => $tenants,
            'periodicityTypes' => $periodicityTypes
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

    public function restore($id): RedirectResponse
    {
        $restored = $this->restorePlan->execute($id);
        return $restored
                ? Redirect::route('panel.plans')->with('success', 'Plan başarılı bir şekilde geri alındı.')
                : Redirect::back()->with('error', 'Plan geri alınırken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function force($id): RedirectResponse
    {
        $forced = $this->forcePlan->execute($id);
        return $forced
                ? Redirect::route('panel.plans')->with('success', 'Plan başarılı bir şekilde tamemen silindi.')
                : Redirect::back()->with('error', 'Plan silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    protected function getPeriodicityTypes(): array
    {
        return [
            PeriodicityType::Day => 'Gün',
            PeriodicityType::Week => 'Hafta',
            PeriodicityType::Month => 'Ay',
            PeriodicityType::Year => 'Yıl'
        ];
    }
}
