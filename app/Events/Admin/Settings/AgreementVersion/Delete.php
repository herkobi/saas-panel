<?php

namespace App\Events\Admin\Settings\AgreementVersion;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\AgreementVersion;
use Illuminate\Contracts\Auth\Authenticatable;

class Delete
{
    use Dispatchable, SerializesModels;

    public $agreementversion;
    public $deletedBy;

    public function __construct(AgreementVersion $agreementversion, Authenticatable $deletedBy)
    {
        $this->agreementversion = $agreementversion;
        $this->deletedBy = $deletedBy;
    }
}
