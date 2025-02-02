<?php

namespace App\Http\Controllers\User\Account\Profile;

use App\Http\Controllers\Controller;
use App\Actions\User\Profile\Email;
use App\Actions\User\Profile\Password;
use App\Actions\User\Profile\Profile;
use App\Http\Requests\User\Profile\MailUpdateRequest;
use App\Http\Requests\User\Profile\PasswordUpdateRequest;
use App\Http\Requests\User\Profile\ProfileUpdateRequest;
use App\Services\User\Profile\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Traits\AuthUser;

class ProfileController extends Controller
{
    use AuthUser;

    protected $userService;
    protected $profileUpdate;
    protected $mailUpdate;
    protected $passwordUpdate;

    public function __construct (
        ProfileService $userService,
        Profile $profileUpdate,
        Email $mailUpdate,
        Password $passwordUpdate,
    ) {
        $this->userService = $userService;
        $this->profileUpdate = $profileUpdate;
        $this->mailUpdate = $mailUpdate;
        $this->passwordUpdate = $passwordUpdate;
        $this->initializeAuthUser();
    }

    public function index(): View
    {
        $user = $this->userService->withMeta($this->user->id);
        return view('user.account.profile.index', [
            'user' => $user
        ]);
    }

    public function update(ProfileUpdateRequest $request, string $id): RedirectResponse
    {
        $updated = $this->profileUpdate->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.profile')->with('success', 'Bilgileriniz başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Bilgileriniz güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function mailUpdate(MailUpdateRequest $request, string $id): RedirectResponse
    {
        $updated = $this->mailUpdate->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.profile')->with('success', 'E-posta adresiniz başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'E-posta adresiniz güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function passwordUpdate(PasswordUpdateRequest $request, string $id): RedirectResponse
    {
        $updated = $this->passwordUpdate->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.profile')->with('success', 'Şifreniz başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Şifreniz güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function twofactor(): View
    {
        return view('user.account.profile.twofactor');
    }

    public function activitylogs(): View
    {
        $user = $this->userService->activitylogs($this->user->id);
        return view('user.account.profile.activitylogs', [
            'user' => $user
        ]);
    }

    public function authlogs(): View
    {
        $user = $this->userService->authLogs($this->user->id);
        return view('user.account.profile.authlogs', [
            'user' => $user
        ]);
    }

 }
