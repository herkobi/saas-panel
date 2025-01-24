<?php

namespace App\Http\Controllers\User\Account\Plans;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Admin\Plan\PlanService;
use App\Traits\AuthUser;
use Illuminate\View\View;

class PlansController extends Controller
{
    use AuthUser;

    protected $planService;

    public function __construct(PlanService $planService)
    {
        $this->initializeAuthUser();
        $this->planService = $planService;
    }

    public function index(): View
    {
        // Get current subscription
        $subscription = $this->user->tenant->subscription;

        // Get pending payment order - tenant_id ile sorgula
        $pendingOrder = Order::where('tenant_id', $this->user->tenant_id)
            ->whereHas('orderstatus', fn($q) => $q->where('code', 'PENDING_PAYMENT'))
            ->first();

        // Tenant'a özel ve genel planlar
        $tenantPlans = $this->planService->getBasePlans($this->user->tenant_id);
        $generalPlans = $this->planService->getFrontPlans()
            ->when($subscription, function($query) use ($subscription) {
                return $query->where('id', '!=', $subscription->plan_id);
            });

        return view('user.account.plans.index', compact(
            'subscription',
            'pendingOrder',
            'tenantPlans',
            'generalPlans'
        ));
    }
}
