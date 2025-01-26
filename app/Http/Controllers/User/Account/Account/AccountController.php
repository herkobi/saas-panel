<?php

namespace App\Http\Controllers\User\Account\Account;

use App\Actions\User\Account\Update as Account;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Account\UserAccountUpdateRequest;
use App\Services\Admin\Tools\CountryService;
use App\Traits\AuthUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AccountController extends Controller
{
    use AuthUser;

    protected $account;
    protected $countryService;

    public function __construct(
        Account $account,
        CountryService $countryService,
    ) {
        $this->initializeAuthUser();
        $this->account = $account;
        $this->countryService = $countryService;
    }

    public function index(): View
    {
        $account = $this->user->account;
        $countries = $this->countryService->getAllCountries();
        return view('user.account.account.index', [
            'account' => $account,
            'countries' => $countries
        ]);
    }

    public function update(UserAccountUpdateRequest $request): RedirectResponse
    {
        $account = $this->user->account->id;
        $updated = $this->account->execute($account, $request->validated());
        return $updated
                ? Redirect::route('app.account')->with('success', 'Hesap bilgileri başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Hesap bilgileri güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
