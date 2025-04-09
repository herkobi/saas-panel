<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ContractType;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        // Üyelik türündeki aktif sözleşmeleri getir
        $membershipContracts = Contract::where('type', ContractType::MEMBERSHIP)
            ->where('status', true)
            ->get();

        return Inertia::render('auth/Register', [
            'membershipContracts' => $membershipContracts
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'contracts' => 'required|array', // Sözleşmelerin kabul edildiğini doğrula
            'contracts.*' => 'required|exists:contracts,id', // Her sözleşme ID'si var mı?
        ]);

        $tenant = Tenant::create([
            'name' => $request->name . " Çalışma Alanı", // Tenant için bir isim
            'status' => true, // Aktif tenant
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => UserType::TENANT_OWNER, // Tenant sahibi olarak ayarla
            'tenant_id' => $tenant->id, // Tenant ile ilişkilendir
        ]);

        event(new Registered($user));

        Auth::login($user);

        return to_route('app.dashboard');
    }
}
