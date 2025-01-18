<?php

namespace App\Http\Controllers\User\Account\Payments;

use App\Actions\User\Order\PlanSwitch;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Payment\PlanSwitchRequest;
use App\Services\OrderService;
use App\Actions\User\Order\Create as CreateOrder;
use App\Models\Plan;
use App\Traits\AuthUser;

class PlanSwitchController extends Controller
{
    use AuthUser;

    protected $orderService;
    protected $createOrder;
    protected $switchOrder;

    public function __construct(
        OrderService $orderService,
        CreateOrder $createOrder,
        PlanSwitch $switchOrder,
    ) {
        $this->initializeAuthUser();
        $this->orderService = $orderService;
        $this->createOrder = $createOrder;
        $this->switchOrder = $switchOrder;
    }

    public function create(Plan $plan)
    {
        // Bekleyen ödeme kontrolü
        if ($this->orderService->hasUncompletedOrders($this->user->tenant_id)) {
            return redirect()
                ->route('app.account.payments.index')
                ->with('error', 'Bekleyen bir ödeme işleminiz bulunmaktadır.');
        }

        // Mevcut planla aynı plan kontrolü
        if ($this->user->tenant->subscription->plan_id === $plan->id) {
            return redirect()
                ->route('app.account.plans')
                ->with('error', 'Zaten bu plana sahipsiniz.');
        }

        return view('user.account.payments.switch', [
            'plan' => $plan,
            'currentPlan' => $this->user->tenant->subscription->plan
        ]);
    }

    public function store(PlanSwitchRequest $request)
    {
        $order = $this->switchOrder->execute($request->validated());
        return match($request->payment_method) {
            'bank' => redirect()->route('app.account.payment.bacs-success', $order->code),
            'credit_card' => redirect()->route('app.account.payment.process', $order->code)
        };
    }
}
