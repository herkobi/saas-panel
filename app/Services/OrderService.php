<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Order;
use App\Models\State;
use App\Repositories\OrderRepository;
use App\Services\Admin\Tools\TaxService;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    protected $repository;
    protected $taxService;

    public function __construct(
        OrderRepository $repository,
        TaxService $taxService
    ) {
        $this->repository = $repository;
        $this->taxService = $taxService;
    }

    public function getOrderById(string $id): Order
    {
        return $this->repository->getOrderById($id);
    }

    public function getAllOrders(): LengthAwarePaginator
    {
        return $this->repository->getAllOrders();
    }

    public function getApprovedOrders(): LengthAwarePaginator
    {
        return $this->repository->getApprovedOrders();
    }

    public function getInvoicedOrders(): LengthAwarePaginator
    {
        return $this->repository->getInvoicedOrders();
    }

    public function getPendingOrders(): LengthAwarePaginator
    {
        return $this->repository->getPendingOrders();
    }

    public function getRejectedOrders(): LengthAwarePaginator
    {
        return $this->repository->getRejectedOrders();
    }

    public function getOrderByCode(string $code): Order
    {
        return $this->repository->getOrderByCode($code);
    }

    public function getTenantOrders(string $tenantId): LengthAwarePaginator
    {
        return $this->repository->getTenantOrders($tenantId);
    }

    public function getUserOrders(string $userId): LengthAwarePaginator
    {
        return $this->repository->getUserOrders($userId);
    }

    public function hasUncompletedOrders(string $tenantId): bool
    {
        return $this->repository->hasUncompletedOrders($tenantId);
    }

    public function calculateOrderTaxes(float $amount, string $countryId, ?string $stateId): array
    {
        $taxes = $this->taxService->getTaxesByRegion($countryId, $stateId);

        $taxData = $taxes->map(function($tax) use ($amount) {
            $taxAmount = round($amount * ($tax->value / 100), 2);
            return [
                'code' => $tax->code,
                'value' => $tax->value,
                'amount' => $taxAmount
            ];
        })->toArray();

        $totalAmount = round($amount + collect($taxData)->sum('amount'), 2);

        return [
            'tax_data' => $taxData,
            'total_amount' => $totalAmount
        ];
    }

    public function createPaymentOrder(array $data): ?Order
    {
        $taxCalculation = $this->calculateOrderTaxes(
            $data['amount'],
            $data['country_id'],
            $data['state_id']
        );

        $country = Country::select('name')->where('id', $data['country_id'])->first();
        $state = State::select('name')->where('id', $data['state_id'])->first();

        $invoiceData = array_intersect_key($data, array_flip([
            'invoice_name', 'tax_number', 'tax_office',
            'address', 'zip_code', 'country_id', 'state_id'
        ]));

        $invoiceData['country_name'] = $country?->name;
        $invoiceData['state_name'] = $state?->name;
        $invoiceData['tax_data'] = $taxCalculation['tax_data'];

        $orderData = [
            'user_id' => $data['user_id'],
            'tenant_id' => $data['tenant_id'],
            'plan_id' => $data['plan_id'],
            'currency_id' => $data['currency_id'],
            'amount' => $data['amount'],
            'total_amount' => $taxCalculation['total_amount'],
            'payment_type' => $data['payment_type'],
            'invoice_data' => $invoiceData,
            'notes' => $data['notes'] ?? null,
            'orderstatus_id' => $data['orderstatus_id']
        ];

        return $this->repository->createOrder($orderData);
    }
    public function updateOrder(string $id, array $data): Order
    {
        return $this->repository->updateOrder($id, $data);
    }

    public function deleteOrder(string $id): void
    {
        $this->repository->delete($id);
    }

    public function approvePayment(string $id): bool
    {
        return $this->repository->approvePayment($id);
    }

    public function rejectPayment(string $id): bool
    {
        return $this->repository->rejectPayment($id);
    }

    public function createSwitchOrder(array $data): ?Order
    {
        $taxCalculation = $this->calculateOrderTaxes(
            $data['amount'],
            $data['country_id'],
            $data['state_id']
        );

        return $this->repository->createSwitchOrder($data, $taxCalculation);
    }
}
