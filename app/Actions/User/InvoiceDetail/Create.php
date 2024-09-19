<?php

namespace App\Actions\User\InvoiceDetail;

use App\Services\User\Account\InvoiceService;
use App\Events\User\Account\InvoiceDetail\Create as Event;
use App\Models\InvoiceDetail;
use App\Traits\AuthUser;

class Create
{
    use AuthUser;

    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): InvoiceDetail
    {
        $invoiceDetail = $this->invoiceService->createInvoiceDetail($data);
        event(new Event($invoiceDetail, $this->user));
        return $invoiceDetail;
    }
}
