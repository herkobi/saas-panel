<?php

namespace App\Services\Tenant;

use App\Enums\UserType;
use App\Models\Activity;
use App\Models\User;
use App\Notifications\UserInvitationNotification;
use App\Traits\AuthUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantUserService
{

    /**
     * Tenant'a ait kullanıcıları getir
     */
    public function getTenantUsers()
    {
        $user = Auth::user();

        if (!$user || !$user->tenant_id) {
            return collect();
        }

        return User::where('tenant_id', $user->tenant_id)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->status,
                    'type' => $user->type->value,
                    'type_label' => $user->type->getLabel(),
                    'is_owner' => $user->isTenantOwner(),
                    'is_staff' => $user->isTenantStaff(),
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at->diffForHumans(),
                ];
            });
    }

    /**
     * Yeni kullanıcı oluştur
     */
    public function createUser(array $data)
    {
        $user = Auth::user();

        if (!$user || !$user->tenant_id) {
            throw new \Exception('Tenant bilgisi bulunamadı.');
        }

        // Staff türü kullanılır
        $userType = UserType::TENANT_STAFF;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'tenant_id' => $user->tenant_id,
            'type' => $userType,
            'status' => true, // Varsayılan olarak aktif
        ]);

        // Aktivite kaydı
        $this->logActivity($user, 'user.created', [
            'action' => 'tenant_staff_created',
            'created_by' => $user->id,
            'tenant_id' => $user->tenant_id,
        ]);

        return $user;
    }

    /**
     * Kullanıcı bilgilerini güncelle
     */
    public function updateUser(User $tenant, array $data)
    {
        $user = Auth::user();

        if (!$user || !$user->tenant_id) {
            throw new \Exception('Tenant bilgisi bulunamadı.');
        }

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        // Şifre değiştirilecekse ekle
        if (isset($data['password']) && !empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        // Status değiştirilecekse ekle
        if (isset($data['status'])) {
            $updateData['status'] = $data['status'];
        }

        $tenant->update($updateData);

        // Aktivite kaydı
        $this->logActivity($tenant, 'user.updated', [
            'action' => 'tenant_staff_updated',
            'updated_by' => $user->id,
            'tenant_id' => $user->tenant_id,
        ]);

        return $user;
    }

    /**
     * Kullanıcı durumunu değiştir (aktif/pasif)
     */
    public function toggleUserStatus(User $tenant, bool $status = false)
    {
        $user = Auth::user();

        // Kullanıcının bu tenant'a ait olduğunu kontrol et
        if ($tenant->tenant_id !== $user->tenant_id) {
            throw new \Exception('Bu kullanıcıya erişim yetkiniz yok.');
        }

        // Owner kendi hesabını devre dışı bırakamaz
        if (!$status && $tenant->isTenantOwner() && $tenant->id === Auth::id()) {
            throw new \Exception('Tenant sahibi hesabını devre dışı bırakamaz.');
        }

        // Durumu güncelle
        $tenant->update(['status' => $status]);

        // Aktivite kaydı
        $action = $status ? 'tenant_staff_activated' : 'tenant_staff_deactivated';
        $message = $status ? 'user.activated' : 'user.deactivated';

        $this->logActivity($tenant, $message, [
            'action' => $action,
            'toggled_by' => $user->id,
            'tenant_id' => $user->tenant_id,
        ]);

        return true;
    }

    /**
     * Davet oluştur ve gönder
     */
    public function createInvitation(array $data)
    {
        $user = Auth::user();

        if (!$user || !$user->tenant_id) {
            throw new \Exception('Tenant bilgisi bulunamadı.');
        }

        // Rastgele şifre oluştur
        $password = Str::random(12);

        // Yeni kullanıcı oluştur
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'tenant_id' => $user->tenant_id,
            'type' => UserType::TENANT_STAFF,
            'status' => true, // Varsayılan olarak aktif
        ]);

        // Davet e-postası gönder
        $this->sendInvitation($user, $password);

        // Aktivite kaydı
        $this->logActivity($user, 'user.invited', [
            'action' => 'tenant_staff_invited',
            'invited_by' => $user->id,
            'tenant_id' => $user->tenant_id,
        ]);

        return $user;
    }

    /**
     * Davet e-postası gönder
     */
    public function sendInvitation(User $user, $password = null)
    {
        // Eğer şifre gönderilmemişse (var olan kullanıcılar için)
        if (!$password) {
            $password = Str::random(12);
            $user->update(['password' => Hash::make($password)]);
        }

        // Tenant bilgisini al
        $tenant = $user->tenant;

        // Davet e-postası gönder
        $user->notify(new UserInvitationNotification($tenant, $password));

        return true;
    }

    /**
     * Aktivite kaydı oluştur
     */
    private function logActivity(User $user, string $message, array $logData)
    {
        Activity::create([
            'tenant_id' => $user->tenant_id,
            'user_id' => $user->id,
            'message' => $message,
            'log' => array_merge($logData, [
                'target_user_id' => $user->id,
                'target_user_email' => $user->email,
                'model' => 'User',
            ]),
        ]);
    }
}
