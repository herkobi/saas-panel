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

    public function index()
    {
        $subscription = $this->user->subscription;
        $pendingOrder = Order::where('user_id', $this->user->id)
            ->whereHas('orderstatus', fn($q) => $q->where('code', 'PENDING'))
            ->first();

        // Tenant'a özel ve genel planlar
        $tenantPlans = $this->planService->getBasePlans($this->user->tenant_id);
        $generalPlans = $this->planService->getFrontPlans();

        return view('user.account.plans.index', compact(
            'subscription',
            'pendingOrder',
            'tenantPlans',
            'generalPlans'
        ));
    }
}
