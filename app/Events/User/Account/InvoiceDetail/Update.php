<?php

namespace App\Events\User\Account\InvoiceDetail;

use App\Models\InvoiceDetail;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $invoiceDetail;
    public $changedBy;
    public $oldData;
    public $newData;

    public function __construct(InvoiceDetail $invoiceDetail, Authenticatable $changedBy, string $oldData, string $newData)
    {
        $this->invoiceDetail = $invoiceDetail;
        $this->changedBy = $changedBy;
        $this->oldData = $oldData;
        $this->newData = $newData;
    }
}
