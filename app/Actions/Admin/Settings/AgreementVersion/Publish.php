<?php

namespace App\Actions\Admin\Settings\AgreementVersion;

use App\Enums\AccountStatus;
use App\Enums\AgreementVersionStatus;
use App\Services\Admin\Settings\AgreementVersionService;
use App\Events\Admin\Settings\AgreementVersion\Publish as Event;
use App\Mail\AgreementVersionPublished;
use App\Models\AgreementVersion;
use App\Models\User;
use App\Traits\AuthUser;
use Illuminate\Support\Facades\Mail;

class Publish
{
    use AuthUser;

    protected $agreementVersionService;

    public function __construct(AgreementVersionService $agreementVersionService)
    {
        $this->agreementVersionService = $agreementVersionService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): AgreementVersion
    {
        // Yayınlamadan önce mevcut published versiyonu alalım
        $oldVersion = $this->agreementVersionService->getAgreementVersionById(id: $id)
            ->agreement
            ->versions()
            ->where('status', AgreementVersionStatus::PUBLISHED)
            ->first();

        // Yayınlama işlemi
        $this->agreementVersionService->publishVersion($id, $data);

        // Yeni (yayınlanan) versiyonu alalım
        $agreementVersion = $this->agreementVersionService->getAgreementVersionById($id);

        // Mail gönderimi
        if ($agreementVersion->send_notification) {
            $users = User::where('type', $agreementVersion->agreement->user_type)
                ->where('status', AccountStatus::ACTIVE)
                ->get();

            foreach ($users as $user) {
                Mail::to($user->email)
                    ->queue(new AgreementVersionPublished($user, $agreementVersion));
            }
        }

        // Event'i tetikleyelim
        event(new Event($agreementVersion, $this->user, $oldVersion));

        return $agreementVersion;
    }
}
