<?php

namespace App\Mail;

use App\Models\AgreementVersion;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgreementVersionPublished extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $version;
    public $agreement;
    public $isAdmin;

    public function __construct(User $user, AgreementVersion $version)
    {
        $this->user = $user;
        $this->version = $version;
        $this->agreement = $version->agreement; // ilişkiyi yüklüyoruz
        $this->isAdmin = $user->type === UserType::ADMIN;
    }

    public function build()
    {
        return $this->markdown($this->isAdmin ? 'vendor.email.newadminagreement' : 'vendor.email.newuseragreement')
            ->with([
                'user' => $this->user,
                'version' => $this->version,
                'agreement' => $this->agreement, // view'a geçiriyoruz
                'isAdmin' => $this->isAdmin
            ])
            ->subject($this->isAdmin
                ? "Yeni Yönetici Sözleşme Versiyonu: {$this->agreement->title}"
                : "Yeni Sözleşme Versiyonu: {$this->agreement->title}");
    }
}
