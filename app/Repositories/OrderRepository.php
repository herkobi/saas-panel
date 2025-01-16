<?php

namespace App\Repositories;

use App\Models\Order;
use App\Services\Admin\Tools\OrderstatusService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository extends BaseRepository
{
    protected $model = Order::class;
    protected $orderStatusService;

    public function __construct(
        OrderstatusService $orderstatusService
    ) {
        $this->orderStatusService = $orderstatusService;
    }

    public function getAllOrders(): LengthAwarePaginator
    {
        return $this->model::with(['user', 'tenant', 'plan', 'orderstatus', 'currency'])
            ->defaultPagination();
    }

    public function getOrderByCode(string $code): Order
    {
        return $this->model::where('code', $code)
            ->with(['user', 'tenant', 'plan', 'orderstatus', 'currency'])
            ->firstOrFail();
    }

    public function getTenantOrders(string $tenantId): LengthAwarePaginator
    {
        return $this->model::where('tenant_id', $tenantId)
            ->with(['user', 'plan', 'orderstatus', 'currency'])
            ->defaultPagination();
    }

    public function getUserOrders(string $userId): LengthAwarePaginator
    {
        return $this->model::where('user_id', $userId)
            ->with(['plan', 'orderstatus', 'currency'])
            ->defaultPagination();
    }

    public function createOrder(array $data): Order
    {
        return $this->model::create([
            'user_id' => $data['user_id'],
            'tenant_id' => $data['tenant_id'],
            'plan_id' => $data['plan_id'],
            'orderstatus_id' => $data['orderstatus_id'],
            'currency_id' => $data['currency_id'],
            'amount' => $data['amount'],
            'total_amount' => $data['total_amount'],
            'payment_type' => $data['payment_type'],
            'invoice_data' => $data['invoice_data'],
            'notes' => $data['notes'] ?? null,
            'payment_date' => $data['payment_date'] ?? null,
        ]);
    }

    public function updateOrder(string $id, array $data): Order
    {
        $order = $this->getById($id);

        $order->update([
            'orderstatus_id' => $data['orderstatus_id'] ?? $order->orderstatus_id,
            'document' => $data['document'] ?? $order->document,
            'notes' => $data['notes'] ?? $order->notes,
            'payment_date' => $data['payment_date'] ?? $order->payment_date,
        ]);

        return $order;
    }

    public function hasUncompletedOrders(string $tenantId): bool
    {
        return $this->model::where('tenant_id', $tenantId)
            ->whereHas('orderstatus', function($query) {
                $query->whereIn('code', ['PENDING_PAYMENT', 'REVIEW']);
            })
            ->exists();
    }

    public function approvePayment(Order $order): ?Order
    {

        $order->update([
            'orderstatus_id' => $this->orderStatusService->getOrderstatusByCode('APPROVED')->id,
            'payment_date' => now()
        ]);

        // Aboneliği yenile/aktifleştir
        $order->user->tenant->subscription->renew();
    }
}
