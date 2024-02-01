<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\User\User;
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

}
