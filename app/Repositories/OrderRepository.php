<?php

namespace App\Repositories;

use App\Models\Order;
use App\Scopes\GlobalQuery;
use App\Services\Admin\Tools\OrderstatusService;
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

    public function getOrderForPanel(string $id): Order
    {
        return $this->model::withoutGlobalScopes()->with(['user', 'orderstatus', 'tenant', 'plan', 'currency'])->findOrFail($id);
    }

    public function getOrderById(string $id): Order
    {
        return $this->model::with(['orderstatus', 'tenant', 'plan', 'currency'])
            ->findOrFail($id);
    }

    public function getAllOrders(): LengthAwarePaginator
    {
        return $this->model::defaultPagination();
    }

    public function getApprovedOrders(): LengthAwarePaginator
    {
        return $this->model::withStatus('APPROVED')->defaultPagination();
    }

    public function getInvoicedOrders(): LengthAwarePaginator
    {
        return $this->model::withStatus('INVOICED')->defaultPagination();
    }

    public function getPendingOrders(): LengthAwarePaginator
    {
        return $this->model::withStatus(['PENDING_PAYMENT', 'REVIEW'])->defaultPagination();
    }

    public function getRejectedOrders(): LengthAwarePaginator
    {
        return $this->model::withStatus('REJECTED')->defaultPagination();
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

    public function approvePayment(string $id): Order
    {
        $order = $this->getOrderForPanel($id);
        $this->model::withoutGlobalScopes()
            ->where('id', $id)  // önemli: spesifik order'ı belirt
            ->update([
                'orderstatus_id' => $this->orderStatusService->getOrderstatusByCode('APPROVED')->id,
                'payment_date' => now()
            ]);
            
        return $order->fresh();
    }

    public function rejectPayment(string $id): bool
    {
        $order = $this->getOrderForPanel($id);
        $rejected = $order->withoutGlobalScopes()->update([
            'orderstatus_id' => $this->orderStatusService->getOrderstatusByCode('REJECTED')->id
        ]);

        if ($rejected) {
            $this->create([
                'user_id' => $order->user_id,
                'tenant_id' => $order->tenant_id,
                'plan_id' => $order->plan_id,
                'currency_id' => $order->currency_id,
                'amount' => $order->amount,
                'total_amount' => $order->total_amount,
                'payment_type' => $order->payment_type,
                'invoice_data' => $order->invoice_data,
                'orderstatus_id' => $this->orderStatusService->getOrderstatusByCode('PENDING_PAYMENT')->id
            ]);
        }

        return $rejected;
    }

    public function createSwitchOrder(array $data, array $taxCalculation): Order
    {
        $orderData = array_merge($data, [
            'total_amount' => $taxCalculation['total_amount'],
            'invoice_data' => array_merge(
                array_intersect_key($data, array_flip([
                    'invoice_name', 'tax_number', 'tax_office',
                    'address', 'zip_code', 'country_id', 'state_id'
                ])),
                [
                    'tax_data' => $taxCalculation['tax_data'],
                    'switch_data' => $data['switch_data']
                ]
            )
        ]);

        return $this->model::create($orderData);
    }
}
