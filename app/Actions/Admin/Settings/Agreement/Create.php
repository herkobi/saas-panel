<?php

namespace App\Actions\Admin\Settings\Agreement;

use App\Models\Agreement;
use App\Services\Admin\Settings\AgreementService;
use App\Events\Admin\Settings\Agreement\Create as Event;
use App\Traits\AuthUser;

class Create
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
        $agreement = $this->agreementService->createAgreement($data);
        event(new Event($agreement, $this->user));
        return $agreement;
    }
}
