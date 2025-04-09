<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait AuthUser
{
    protected ?User $user;

    protected function initializeAuthUser()
    {
        $this->user = Auth::user();

        // Kullanıcı giriş yapmış ve tenant_id'si varsa
        if ($this->user && $this->user->tenant_id) {
            // Tenant ilişkisini lazy load et - sadece gerektiğinde yüklenecek
            if (!$this->user->relationLoaded('tenant')) {
                $this->user->load('tenant');
            }
        }
    }
}
