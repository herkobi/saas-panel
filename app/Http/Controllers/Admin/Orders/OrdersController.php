<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Actions\Admin\Order\Approve;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\Admin\Tools\OrderStatusService;
use App\Actions\Admin\Order\ApprovePayment;
use App\Traits\AuthUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrdersController extends Controller
{
    use AuthUser;
    protected $orderService;
    protected $orderStatusService;
    protected $approvePayment;

    public function __construct(
        OrderService $orderService,
        OrderStatusService $orderStatusService,
        Approve $approvePayment
    ) {
        $this->initializeAuthUser();
        $this->orderService = $orderService;
        $this->orderStatusService = $orderStatusService;
        $this->approvePayment = $approvePayment;
    }

    public function index(): View
    {
        $orders = $this->orderService->getAllOrders();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', compact('order'));
    }

    public function approve(Order $order): RedirectResponse
    {
        // Sadece banka ödemesi bekleyen orderları onayla
        if ($order->payment_type !== 'bank' ||
            !$order->orderstatus->code === 'PENDING_PAYMENT') {
            return redirect()
                ->back()
                ->with('error', 'Geçersiz işlem.');
        }

        // Tenant ve owner kontrolü
        if (!$this->user->is_tenant_owner || $order->tenant_id !== $this->user->tenant_id) {
            return redirect()
                ->back()
                ->with('error', 'Bu işlem için yetkiniz bulunmuyor.');
        }

        $result = $this->approvePayment->execute($order);

        return $result
            ? redirect()
                ->back()
                ->with('success', 'Ödeme onaylandı ve abonelik yenilendi.')
            : redirect()
                ->back()
                ->with('error', 'Ödeme onaylanırken bir hata oluştu.');
    }
}
