<?php

namespace App\Events\User\Account\InvoiceDetail;

use App\Models\InvoiceDetail;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $invoiceDetail;
    public $createdBy;

    public function __construct(InvoiceDetail $invoiceDetail, Authenticatable $createdBy)
    {
        $this->invoiceDetail = $invoiceDetail;
        $this->createdBy = $createdBy;
    }
}
