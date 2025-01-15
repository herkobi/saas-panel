<?php

namespace App\Events\Admin\Settings\Agreement;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Agreement;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $agreement;
    public $changedBy;
    public $oldAgreement;
    public $newAgreement;

    public function __construct(Agreement $agreement, Authenticatable $changedBy, string $oldAgreement, string $newAgreement)
    {
        $this->agreement = $agreement;
        $this->changedBy = $changedBy;
        $this->oldAgreement = $oldAgreement;
        $this->newAgreement = $newAgreement;
    }
}
