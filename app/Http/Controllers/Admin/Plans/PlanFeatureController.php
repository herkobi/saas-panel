<?php

namespace App\Http\Controllers\Admin\Plans;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Admin\Feature;
use App\Models\Admin\Plan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use LucasDotVin\Soulbscription\Models\FeaturePlan;

class PlanFeatureController extends Controller
{
    public function index(Plan $plan) : View
    {
        $features = Feature::where('status', Status::ACTIVE)->get();
        $attached_features = FeaturePlan::where('plan_id', $plan->id)->get();

        return view('admin.plans.plan_feature.index', [
            'plan' => $plan,
            'features' => $features,
            'attached_features' => $attached_features
        ]);
    }

    public function update(Request $request, Plan $plan)
    {

        $validatedData = $request->validate([
            'selected.*' => 'nullable|exists:App\Models\Admin\Feature,id', // Seçili özelliklerin geçerli olduğunu kontrol et
            'usage_limit.*' => 'nullable|numeric', // Kullanım limitinin sayısal olup olmadığını kontrol et
            'usage_quota.*' => 'nullable|numeric', // Kullanım kotasının sayısal olup olmadığını kontrol et
        ]);

        $selectedFeatures = $validatedData['selected'];
        $usageLimits = $validatedData['usage_limit'] ?? null;
        $usageQuotas = $validatedData['usage_quota'] ?? null;

        foreach ($selectedFeatures as $index => $featureId) {

            $currentFeature = Feature::find($featureId);

            $currentLimit = $usageLimits[$index] ?? null;
            $currentQuota = $usageQuotas[$index] ?? null;

            if($currentFeature->consumable == 0 && $currentFeature->quota == 0) {
                $plan->features()->attach($currentFeature);
            } else {
                $plan->features()->attach($currentFeature);
                $plan->features()->attach($currentFeature, ['charges' => $currentLimit] );
                $plan->features()->attach($currentFeature, ['charges' => $currentQuota] );
            }

            return Redirect::route('panel.plans.list')->with('success', __('admin/plans/plans.feaures.attached.to.plan'));
        }

    }

    public function history(Plan $plan) : View
    {
        return view('admin.plans.plan_feature.history', [
            'plan' => $plan
        ]);
    }
}
