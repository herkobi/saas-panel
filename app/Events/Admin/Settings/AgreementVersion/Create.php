<?php

namespace App\Events\Admin\Settings\AgreementVersion;

use App\Models\AgreementVersion;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $agreementversion;
    public $createdBy;

    public function __construct(AgreementVersion $agreementversion, Authenticatable $createdBy)
    {
        $this->agreementversion = $agreementversion;
        $this->createdBy = $createdBy;
    }
}
