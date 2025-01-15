<?php

namespace App\Actions\Admin\Settings\AgreementVersion;

use App\Models\AgreementVersion;
use App\Services\Admin\Settings\AgreementVersionService;
use App\Events\Admin\Settings\AgreementVersion\Create as Event;
use App\Traits\AuthUser;

class Create
{
    use AuthUser;

    protected $agreementversionService;

    public function __construct(AgreementVersionService $agreementversionService)
    {
        $this->agreementversionService = $agreementversionService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): AgreementVersion
    {
        $agreementversion = $this->agreementversionService->createAgreementVersion($id, $data);
        event(new Event($agreementversion, $this->user));
        return $agreementversion;
    }
}
