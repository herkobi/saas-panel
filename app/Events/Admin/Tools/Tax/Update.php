<?php

namespace App\Events\Admin\Tools\Tax;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Tax;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $tax;
    public $changedBy;
    public $oldTax;
    public $newTax;

    public function __construct(Tax $tax, Authenticatable $changedBy, string $oldTax, string $newTax)
    {
        $this->tax = $tax;
        $this->changedBy = $changedBy;
        $this->oldTax = $oldTax;
        $this->newTax = $newTax;
    }
}
