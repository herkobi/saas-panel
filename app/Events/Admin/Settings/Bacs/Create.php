<?php

namespace App\Events\Admin\Settings\Bacs;

use App\Models\Bacs;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $bacs;
    public $createdBy;

    public function __construct(Bacs $bacs, Authenticatable $createdBy)
    {
        $this->bacs = $bacs;
        $this->createdBy = $createdBy;
    }
}
