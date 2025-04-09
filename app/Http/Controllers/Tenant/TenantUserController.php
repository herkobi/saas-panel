<?php

namespace App\Http\Controllers\Tenant;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\Tenant\TenantUserService;
use App\Http\Requests\Tenant\Subscription\Users\UserInviteRequest;
use App\Http\Requests\Tenant\Subscription\Users\UserCreateRequest;
use App\Http\Requests\Tenant\Subscription\Users\UserUpdateRequest;
use Inertia\Inertia;

class TenantUserController extends Controller
{
    public function __construct(protected TenantUserService $tenantUserService)
    {
    }

    /**
     * Kullanıcı listesini göster
     */
    public function index()
    {
        $users = $this->tenantUserService->getTenantUsers();

        return Inertia::render('tenant/subscription/users/Users', [
            'users' => $users
        ]);
    }

    /**
     * Yeni kullanıcı ekleme formu
     */
    public function create()
    {
        return Inertia::render('tenant/subscription/users/UserForm', [
            'user' => null
        ]);
    }

    /**
     * Kullanıcı kaydet
     */
    public function store(UserCreateRequest $request)
    {
        $data = $request->validated();
        $user = $this->tenantUserService->createUser($data);

        if ($request->has('send_invitation') && $request->send_invitation) {
            $this->tenantUserService->sendInvitation($user);
        }

        return redirect()->route('app.subscription.users.index')
            ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    /**
     * Kullanıcı düzenleme formu
     */
    public function edit(User $user)
    {
        // Kullanıcının bu tenant'a ait olduğunu doğrula
        $this->authorize('edit', $user);

        return Inertia::render('tenant/subscription/users/UserForm', [
            'user' => $user
        ]);
    }

    /**
     * Kullanıcı güncelle
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        // Kullanıcının bu tenant'a ait olduğunu doğrula
        $this->authorize('update', $user);

        $data = $request->validated();
        $this->tenantUserService->updateUser($user, $data);

        return redirect()->route('app.subscription.users.index')
            ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    /**
     * Kullanıcıyı pasif hale getir
     */
    public function deactivate(User $user)
    {
        // Kullanıcının bu tenant'a ait olduğunu doğrula
        $this->authorize('update', $user);

        $this->tenantUserService->toggleUserStatus($user, false);

        return redirect()->route('app.subscription.users.index')
            ->with('success', 'Kullanıcı başarıyla devre dışı bırakıldı.');
    }

    /**
     * Kullanıcıyı aktif hale getir
     */
    public function activate(User $user)
    {
        // Kullanıcının bu tenant'a ait olduğunu doğrula
        $this->authorize('update', $user);

        $this->tenantUserService->toggleUserStatus($user, true);

        return redirect()->route('app.subscription.users.index')
            ->with('success', 'Kullanıcı başarıyla aktifleştirildi.');
    }

    /**
     * Davet gönderme sayfası
     */
    public function invite()
    {
        return Inertia::render('tenant/subscription/users/UserInvite');
    }

    /**
     * Davet gönder
     */
    public function sendInvite(UserInviteRequest $request)
    {
        $data = $request->validated();
        $user = $this->tenantUserService->createInvitation($data);

        return redirect()->route('app.subscription.users.index')
            ->with('success', 'Davet başarıyla gönderildi.');
    }
}
