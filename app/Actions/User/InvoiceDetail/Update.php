<?php

namespace App\Actions\User\InvoiceDetail;

use App\Services\User\Account\InvoiceService;
use App\Events\User\Account\InvoiceDetail\Update as Event;
use App\Models\InvoiceDetail;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): InvoiceDetail
    {
        $oldData = $this->invoiceService->getInvoiceDetail($id);
        $invoiceDetail = $this->invoiceService->updateInvoiceDetail($id, $data);
        $newData = $this->invoiceService->getInvoiceDetail($id);
        event(new Event($invoiceDetail, $this->user, $oldData, $newData));
        return $invoiceDetail;
    }
}
