<?php

namespace App\Actions\Admin\Settings\Agreement;

use App\Services\Admin\Settings\AgreementService;
use App\Events\Admin\Settings\Agreement\Accept as Event;
use App\Models\Agreement;
use App\Traits\AuthUser;

class Accept
{
    use AuthUser;

    protected $agreementService;

    public function __construct(AgreementService $agreementService)
    {
        $this->agreementService = $agreementService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): Agreement
    {
        $agreement = $this->agreementService->acceptAgreement($data);
        event(new Event($agreement, $this->user));
        return $agreement;
    }
}
