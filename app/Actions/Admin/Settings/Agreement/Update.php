<?php

namespace App\Actions\Admin\Settings\Agreement;

use App\Services\Admin\Settings\AgreementService;
use App\Events\Admin\Settings\Agreement\Update as Event;
use App\Models\Agreement;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $agreementService;

    public function __construct(AgreementService $agreementService)
    {
        $this->agreementService = $agreementService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): Agreement
    {
        $oldAgreement = $this->agreementService->getAgreementById($id);
        $agreement = $this->agreementService->updateAgreement($id, $data);
        $newAgreement = $this->agreementService->getAgreementById($id);
        event(new Event($agreement, $this->user, $oldAgreement, $newAgreement));
        return $agreement;
    }
}
