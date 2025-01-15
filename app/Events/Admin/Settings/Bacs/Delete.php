<?php

namespace App\Events\Admin\Settings\Bacs;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Bacs;
use Illuminate\Contracts\Auth\Authenticatable;

class Delete
{
    use Dispatchable, SerializesModels;

    public $bacs;
    public $deletedBy;

    public function __construct(Bacs $bacs, Authenticatable $deletedBy)
    {
        $this->bacs = $bacs;
        $this->deletedBy = $deletedBy;
    }
}
