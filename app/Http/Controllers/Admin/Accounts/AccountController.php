<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $accounts = User::latest()->take(50)->get();
        return view('admin.accounts.index', [
            'accounts' => $accounts
        ]);
    }

    public function create(): View
    {
        return view('admin.accounts.create');
    }

    // public function store(AccountCreateRequest $request): RedirectResponse
    // {

    // }

    public function edit(User $user): View
    {
        return view('admin.accounts.edit', [
            'user' => $user
        ]);
    }

    // public function store(AccountUpdateRequest $request, User $user): RedirectResponse
    // {

    // }

}
