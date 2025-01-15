<?php

namespace App\Events\Admin\Settings\Bacs;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Bacs;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $bacs;
    public $changedBy;
    public $oldBacs;
    public $newBacs;

    public function __construct(Bacs $bacs, Authenticatable $changedBy, string $oldBacs, string $newBacs)
    {
        $this->bacs = $bacs;
        $this->changedBy = $changedBy;
        $this->oldBacs = $oldBacs;
        $this->newBacs = $newBacs;
    }
}
