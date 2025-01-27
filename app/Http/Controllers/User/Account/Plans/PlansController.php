<?php

namespace App\Http\Controllers\User\Account\Plans;

use App\Actions\User\Plan\SwitchPlan;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Admin\Plan\PlanService;
use App\Services\User\PlanService as UserPlanService;
use App\Traits\AuthUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlansController extends Controller
{
    use AuthUser;

    protected $planService;
    protected $switchPlan;

    public function __construct(
        PlanService $planService,
        SwitchPlan $switchPlan,
    ) {
        $this->initializeAuthUser();
        $this->planService = $planService;
        $this->switchPlan = $switchPlan;
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

    public function switch(string $id)
    {
        $plan = $this->planService->getPlanById($id);

        // Aynı plan kontrolü
        if ($this->user->tenant->subscription->plan_id === $plan->id) {
            return redirect()
                ->route('app.account.plans')
                ->with('error', 'Zaten bu plana sahipsiniz.');
        }

        // Ücretsizden ücretliye geçiş için fatura bilgileri kontrolü
        if ($plan->price > 0 && !$this->user->tenant->hasCompleteInvoiceData()) {
            return redirect()
                ->route('app.account.account')
                ->with('warning', 'Plan geçişi yapmadan önce fatura bilgilerinizi doldurmanız gerekmektedir.');
        }

        $switched = $this->switchPlan->execute($plan->id);

        if ($switched instanceof RedirectResponse) {
            return $switched; // Ödeme sayfasına yönlendirme
        }

        return redirect()
            ->route('app.account.plans')
            ->with('success', $switched['message']);
            }
}
