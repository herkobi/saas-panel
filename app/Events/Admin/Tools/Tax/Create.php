<?php

namespace App\Events\Admin\Tools\Tax;

use App\Models\Tax;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $tax;
    public $createdBy;

    public function __construct(Tax $tax, Authenticatable $createdBy)
    {
        $this->tax = $tax;
        $this->createdBy = $createdBy;
    }
}
