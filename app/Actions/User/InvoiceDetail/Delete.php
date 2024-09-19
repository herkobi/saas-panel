<?php

namespace App\Actions\User\InvoiceDetail;

use App\Services\User\Account\InvoiceService;
use App\Events\User\Account\InvoiceDetail\Delete as Event;
use App\Models\InvoiceDetail;
use App\Traits\AuthUser;

class Delete
{
    use AuthUser;

    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
        $this->initializeAuthUser();
    }

    public function execute(string $id): InvoiceDetail
    {
        $invoiceDetail = $this->invoiceService->getInvoiceDetail($id);
        $this->invoiceService->deleteInvoiceDetail($id);
        event(new Event($invoiceDetail, $this->user));
        return $invoiceDetail;
    }
}
