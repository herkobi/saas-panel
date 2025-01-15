<?php

namespace App\Events\Admin\Settings\AgreementVersion;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\AgreementVersion;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $agreementversion;
    public $changedBy;
    public $oldAgreementVersion;
    public $newAgreementVersion;

    public function __construct(AgreementVersion $agreementversion, Authenticatable $changedBy, string $oldAgreementVersion, string $newAgreementVersion)
    {
        $this->agreementversion = $agreementversion;
        $this->changedBy = $changedBy;
        $this->oldAgreementVersion = $oldAgreementVersion;
        $this->newAgreementVersion = $newAgreementVersion;
    }
}
