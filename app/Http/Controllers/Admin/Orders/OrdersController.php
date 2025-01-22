<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Models\Order;
use App\Actions\Admin\Order\Approve;
use App\Actions\Admin\Order\Reject;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Http\Requests\Admin\Order\OrderApproveRequest;
use App\Http\Requests\Admin\Order\OrderRejectRequest;
use App\Services\Admin\Tools\OrderStatusService;
use App\Traits\AuthUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
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

    public function detail(string $id): View
    {
        $order = $this->orderService->getOrderById($id);
        return view('admin.orders.detail', [
            'order' => $order
        ]);
    }

    public function approve(OrderApproveRequest $request, string $id): RedirectResponse
    {
        $result = $this->approvePayment->execute($id);

        return $result
            ? Redirect::back()
                ->with('success', 'Ödeme onaylandı ve abonelik yenilendi.')
            : Redirect::back()
                ->with('error', 'Ödeme onaylanırken bir hata oluştu.');
    }

    public function reject(OrderRejectRequest $request, string $id): RedirectResponse
    {
        $result = $this->rejectPayment->execute($id);

        return $result
            ? Redirect::back()
                ->with('success', 'Ödeme reddedildi ve yeni ödeme kaydı oluşturuldu.')
            : Redirect::back()
                ->with('error', 'Ödeme reddedilirken bir hata oluştu.');
    }
}
