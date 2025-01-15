<?php

namespace App\Actions\Admin\Settings\Agreement;

use App\Services\Admin\Settings\AgreementService;
use App\Events\Admin\Settings\Agreement\Delete as Event;
use App\Models\Agreement;
use App\Traits\AuthUser;

class Delete
{
    use AuthUser;

    protected $agreementService;

    public function __construct(AgreementService $agreementService)
    {
        $this->agreementService = $agreementService;
        $this->initializeAuthUser();
    }

    public function execute(string $id): Agreement
    {
        $agreement = $this->agreementService->getAgreementById($id);
        $this->agreementService->deleteAgreement($id);
        event(new Event($agreement, $this->user));
        return $agreement;
    }
}
