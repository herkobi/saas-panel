<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Actions\Admin\Accounts\Create;
use App\Actions\Admin\Accounts\ChangeEmail;
use App\Actions\Admin\Accounts\CheckEmail;
use App\Actions\Admin\Accounts\ResetPassword;
use App\Actions\Admin\Accounts\StatusUpdate;
use App\Actions\Admin\Accounts\VerifyEmail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounts\StatusUpdateRequest;
use App\Services\Admin\Accounts\AccountService;
use App\Services\Admin\Settings\UserService;
use App\Services\Admin\Tools\AuthLogsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TenantsController extends Controller
{
    protected $tenantService;
    protected $accountService;
    protected $authLogs;
    protected $createUser;
    protected $statusUpdate;
    protected $changeEmail;
    protected $verifyEmail;
    protected $checkEmail;
    protected $resetPassword;

    public function __construct(
        UserService $tenantService,
        AuthLogsService $authLogs,
        AccountService $accountService,
        Create $createUser,
        StatusUpdate $statusUpdate,
        ChangeEmail $changeEmail,
        VerifyEmail $verifyEmail,
        CheckEmail $checkEmail,
        ResetPassword $resetPassword,
    ) {
        $this->tenantService = $tenantService;
        $this->accountService = $accountService;
        $this->authLogs = $authLogs;
        $this->createUser = $createUser;
        $this->statusUpdate = $statusUpdate;
        $this->changeEmail = $changeEmail;
        $this->verifyEmail = $verifyEmail;
        $this->checkEmail = $checkEmail;
        $this->resetPassword = $resetPassword;
    }

    public function index(): View
    {
        $users = $this->tenantService->getAccounts();
        return view('admin.accounts.index', [
            'users' => $users
        ]);
    }

    public function detail(string $id): View
    {
        $user = $this->tenantService->getUserActivity($id);
        return view('admin.accounts.detail', [
            'user' => $user,
            'activities' => $user->activities
        ]);
    }

    public function latest(): View
    {
        $users = $this->accountService->getLastThirtyDaysActiveMembers();
        return view('admin.accounts.latest', [
            'users' => $users
        ]);
    }

    public function inactive(): View
    {
        $users = $this->accountService->getInactiveActiveUsers();
        return view('admin.accounts.inactive', [
            'users' => $users
        ]);
    }

    public function draft(): View
    {
        $users = $this->accountService->getDraftUsers();
        return view('admin.accounts.draft', [
            'users' => $users
        ]);
    }

    public function passive(): View
    {
        $users = $this->accountService->getPassiveUsers();
        return view('admin.accounts.passive', [
            'users' => $users
        ]);
    }

    public function deleted(): View
    {
        $users = $this->accountService->getDeletedUsers();
        return view('admin.accounts.deleted', [
            'users' => $users
        ]);
    }

    public function statusUpdate(StatusUpdateRequest $request, $id): RedirectResponse
    {
        $updated = $this->statusUpdate->execute($id, $request->validated());
        return $updated
            ? Redirect::back()->with('success', 'Kullanıcı durumu başarılı bir şekilde güncellendi.')
            : Redirect::back()->with('error', 'Hata; Lütfen geçerli bir durum seçiniz');
    }
}
