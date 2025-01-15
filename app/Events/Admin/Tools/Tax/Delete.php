<?php

namespace App\Events\Admin\Tools\Tax;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Tax;
use Illuminate\Contracts\Auth\Authenticatable;

class Delete
{
    use Dispatchable, SerializesModels;

    public $tax;
    public $deletedBy;

    public function __construct(Tax $tax, Authenticatable $deletedBy)
    {
        $this->tax = $tax;
        $this->deletedBy = $deletedBy;
    }
}
