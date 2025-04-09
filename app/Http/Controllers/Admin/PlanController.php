<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Plan\PlanCreateRequest;
use App\Http\Requests\Admin\Plan\PlanUpdateRequest;
use App\Models\Plan;
use App\Services\Admin\FeatureService;
use App\Services\Admin\PlanService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class PlanController extends Controller
{
    public function __construct(protected PlanService $planService, protected FeatureService $featureService)
    {
    }

    /**
     * Planların listesini göster.
     */
    public function index()
    {
        $plans = $this->planService->getAllPlans();

        return Inertia::render('admin/plans/Index', [
            'plans' => $plans->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'monthly_price' => $plan->monthly_price,
                    'yearly_price' => $plan->yearly_price,
                    'is_featured' => $plan->is_featured,
                    'is_free' => $plan->is_free,
                    'status' => $plan->status,
                    'feature_count' => $plan->planFeatures->count(),
                    'sort_order' => $plan->sort_order,
                    'created_at' => $plan->created_at->format('Y-m-d'),
                ];
            }),
        ]);
    }

    /**
     * Yeni plan oluşturma formunu göster.
     */
    public function create()
    {
        $features = $this->featureService->getActiveFeatures();
        $currencies = config('tenant.currencies');
        $countries = config('tenant.countries');
        $taxRates = config('tenant.tax_rates');

        return Inertia::render('admin/plans/Create', [
            'features' => $features->map(function ($feature) {
                return [
                    'id' => $feature->id,
                    'name' => $feature->name,
                    'slug' => $feature->slug,
                    'description' => $feature->description,
                ];
            }),
            'currencies' => $currencies,
            'countries' => $countries,
            'taxRates' => $taxRates,
        ]);
    }

    /**
     * Yeni planı veritabanına kaydet.
     */
    public function store(PlanCreateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Özellikleri ayır
        $planFeatures = $validated['planFeatures'] ?? [];
        unset($validated['planFeatures']);

        try {
            $this->planService->create($validated, $planFeatures);

            return redirect()->route('panel.plans.index')
                ->with('success', 'Plan başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Plan oluşturulurken bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Plan düzenleme formunu göster.
     */
    public function edit(Plan $plan)
    {
        $plan = $this->planService->getPlanWithDetails($plan);
        $features = $this->featureService->getActiveFeatures();
        $currencies = config('tenant.currencies');
        $countries = config('tenant.countries');
        $taxRates = config('tenant.tax_rates');

        return Inertia::render('admin/plans/Edit', [
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'description' => $plan->description,
                'is_featured' => $plan->is_featured,
                'is_free' => $plan->is_free,
                'billing_period' => $plan->billing_period,
                'country_code' => $plan->country_code,
                'currency_code' => $plan->currency_code,
                'tax_rate_code' => $plan->tax_rate_code,
                'monthly_price' => $plan->monthly_price,
                'yearly_price' => $plan->yearly_price,
                'trial_days' => $plan->trial_days,
                'grace_period_days' => $plan->grace_period_days,
                'payment_timing' => $plan->payment_timing,
                'status' => $plan->status,
                'sort_order' => $plan->sort_order,
                'planFeatures' => $plan->planFeatures->map(function ($planFeature) {
                    return [
                        'id' => $planFeature->id,
                        'feature_id' => $planFeature->feature_id,
                        'feature_name' => $planFeature->feature->name,
                        'access_type' => $planFeature->access_type,
                        'limit_type' => $planFeature->limit_type,
                        'limit_value' => $planFeature->limit_value,
                        'limit_reset_period' => $planFeature->limit_reset_period,
                        'restore_on_delete' => $planFeature->restore_on_delete,
                    ];
                }),
            ],
            'features' => $features->map(function ($feature) {
                return [
                    'id' => $feature->id,
                    'name' => $feature->name,
                    'slug' => $feature->slug,
                    'description' => $feature->description,
                ];
            }),
            'currencies' => $currencies,
            'countries' => $countries,
            'taxRates' => $taxRates,
        ]);
    }

    /**
     * Planı güncelle.
     */
    public function update(PlanUpdateRequest $request, Plan $plan): RedirectResponse
    {
        $validated = $request->validated();

        // Özellikleri ayır
        $planFeatures = $validated['planFeatures'] ?? [];
        unset($validated['planFeatures']);

        try {
            $this->planService->update($plan, $validated, $planFeatures);

            return redirect()->route('panel.plans.index')
                ->with('success', 'Plan başarıyla güncellendi.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Plan güncellenirken bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Planı sil.
     */
    public function destroy(Plan $plan): RedirectResponse
    {
        try {
            $this->planService->delete($plan);

            return redirect()->route('panel.plans.index')
                ->with('success', 'Plan başarıyla silindi.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Plan silinirken bir hata oluştu: ' . $e->getMessage()]);
        }
    }
}
