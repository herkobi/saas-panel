<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{

    public function index(): View
    {
        $users = Admin::all();
        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function edit(Admin $admin): View
    {
        return view('admin.users.edit', [
            'user' => $admin
        ]);
    }
}
