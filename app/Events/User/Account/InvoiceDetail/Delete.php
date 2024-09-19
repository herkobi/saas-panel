<?php

namespace App\Events\User\Account\InvoiceDetail;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\InvoiceDetail;
use Illuminate\Contracts\Auth\Authenticatable;

class Delete
{
    use Dispatchable, SerializesModels;

    public $invoiceDetail;
    public $deletedBy;

    public function __construct(InvoiceDetail $invoiceDetail, Authenticatable $deletedBy)
    {
        $this->invoiceDetail = $invoiceDetail;
        $this->deletedBy = $deletedBy;
    }
}
