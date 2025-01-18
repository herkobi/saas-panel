<?php

namespace App\Actions\Fortify;

use App\Enums\AccountStatus;
use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Agreement;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Str;
use App\Services\User\TenantService;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function create(array $input): User
    {
        $registerAgreements = Agreement::where('user_type', UserType::USER)
            ->where('show_on_register', true)
            ->where('status', Status::ACTIVE)
            ->get();

        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ];

        // Domain validasyonu subdomain aktifse
        if (config('tenant.use_subdomain')) {
            $validationRules['domain'] = ['nullable', 'string', 'unique:tenants,domain'];
        }

        // Sözleşme validasyonları
        foreach ($registerAgreements as $agreement) {
            $validationRules['agreement_' . $agreement->id] = ['required', 'accepted'];
        }

        Validator::make($input, $validationRules)->validate();

        // Önce tenant oluştur
        $tenant = $this->tenantService->createForUser([
            'domain' => $input['domain'] ?? null
        ]);

        // Kullanıcıyı tenant ile ilişkilendir
        $user = User::create([
            'status' => AccountStatus::ACTIVE,
            'tenant_id' => $tenant->id,
            'is_tenant_owner' => true, // Tenant sahibi mi
            'type' => UserType::USER,
            'name' => $input['name'],
            'surname' => $input['surname'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'created_by' => 0,
            'created_by_name' => 'Owner'
        ]);

        // Kullanıcı klasörünü tenant altında oluştur
        $folderName = 'user_' . Str::random(12);
        Storage::makeDirectory($tenant->getUserPath($folderName));

        // User meta
        $user->meta()->create([
            'title' => null,
            'user_folder' => $folderName
        ]);

        // User account
        $user->account()->create([
            'invoice_name' => $user->name . ' ' . $user->surname
        ]);

        // Sözleşmeleri ekle
        foreach ($registerAgreements as $agreement) {
            if (isset($input['agreement_' . $agreement->id])) {
                $latestVersion = $agreement->latestVersion();
                if ($latestVersion) {
                    $user->agreements()->attach($agreement->id, [
                        'id' => Str::uuid(),
                        'agreement_version_id' => $latestVersion->id,
                        'accepted_at' => now(),
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent()
                    ]);
                }
            }
        }

        // Plan subscription
        if (session()->has('selected_plan')) {
            $plan = Plan::where('id', session('selected_plan'))->first();
            $subscription = $tenant->subscribeTo($plan);

            if ($plan->price > 0 && !$plan->has_postpaid_feature) {
                $subscription->suppress();
            }
        }

        return $user;
    }
}
