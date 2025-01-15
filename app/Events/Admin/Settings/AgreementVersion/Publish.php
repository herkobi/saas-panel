<?php

namespace App\Events\Admin\Settings\AgreementVersion;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\AgreementVersion;
use Illuminate\Contracts\Auth\Authenticatable;

class Publish
{
    use Dispatchable, SerializesModels;

    public $agreementVersion;
    public $publishedBy;
    public $oldVersion;

    public function __construct(AgreementVersion $agreementVersion, Authenticatable $publishedBy, ?AgreementVersion $oldVersion)
    {
        $this->agreementVersion = $agreementVersion;
        $this->publishedBy = $publishedBy;
        $this->oldVersion = $oldVersion;
    }
}
