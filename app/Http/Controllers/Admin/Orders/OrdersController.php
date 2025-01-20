<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Actions\Admin\Order\Approve;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\Admin\Tools\OrderStatusService;
use App\Actions\Admin\Order\Reject;
use App\Traits\AuthUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrdersController extends Controller
{
    use AuthUser;
    protected $orderService;
    protected $orderStatusService;
    protected $approvePayment;
    protected $rejectPayment;

    public function __construct(
        OrderService $orderService,
        OrderStatusService $orderStatusService,
        Approve $approvePayment,
        Reject $rejectPayment,
    ) {
        $this->initializeAuthUser();
        $this->orderService = $orderService;
        $this->orderStatusService = $orderStatusService;
        $this->approvePayment = $approvePayment;
        $this->rejectPayment = $rejectPayment;
    }

    public function index(): View
    {
        $orders = $this->orderService->getApprovedOrders();
        return view('admin.orders.index', [
            'orders' => $orders
        ]);
    }

    public function invoiced(): View
    {
        $orders = $this->orderService->getInvoicedOrders();
        return view('admin.orders.invoiced', [
            'orders' => $orders
        ]);
    }

    public function pending(): View
    {
        $orders = $this->orderService->getPendingOrders();
        return view('admin.orders.pending', [
            'orders' => $orders
        ]);
    }

    public function rejected(): View
    {
        $orders = $this->orderService->getRejectedOrders();
        return view('admin.orders.rejected', [
            'orders' => $orders
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', compact('order'));
    }

    public function approve(Order $order): RedirectResponse
    {
        // Onay kontrolü
        if ($order->payment_type !== 'bank' ||
            $order->orderstatus->code !== 'PENDING_PAYMENT') {
            return redirect()
                ->back()
                ->with('error', 'Geçersiz işlem.');
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

    public function reject(Order $order): RedirectResponse
    {
        // Onay kontrolü
        if ($order->payment_type !== 'bank' ||
            !in_array($order->orderstatus->code, ['PENDING_PAYMENT', 'REVIEW'])) {
            return redirect()
                ->back()
                ->with('error', 'Geçersiz işlem.');
        }

        $result = $this->rejectPayment->execute($order);

        return $result
            ? redirect()
                ->back()
                ->with('success', 'Ödeme reddedildi ve yeni ödeme kaydı oluşturuldu.')
            : redirect()
                ->back()
                ->with('error', 'Ödeme reddedilirken bir hata oluştu.');
    }
}
