<?php

namespace App\Http\Controllers\Admin\Users;

use App\Enums\Admin as AdminStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\AdminCreateRequest;
use App\Http\Requests\Admin\Users\AdminUpdateRequest;
use App\Models\Admin\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
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

    public function store(AdminCreateRequest $request): RedirectResponse
    {
        $admin = Admin::create([
            'status' => AdminStatus::ACTIVE,
            'name' => $request->name,
            'surname' => $request->surname,
            'title' => $request->title,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return Redirect::route('panel.users')->with('success', __('admin/users/user.store.success'));
    }

    public function edit(Admin $admin): View|RedirectResponse
    {
        if(Auth::user()->id == $admin->id) {
            return Redirect::route('panel.profile.edit');
        }

        return view('admin.users.edit', [
            'user' => $admin
        ]);
    }

    public function update(AdminUpdateRequest $request, Admin $admin): RedirectResponse
    {
        $admin->update([
            'status' => $request->status,
            'name' => $request->name,
            'surname' => $request->surname,
            'title' => $request->title,
            'email' => $request->email,
            'username' => $request->username,
        ]);

        return Redirect::route('panel.users')->with('success', __('admin/users/user.update.success'));
    }
}
