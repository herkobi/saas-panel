<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Payment;
use App\Models\Tenant;
use App\Services\Admin\TenantService;
use App\Services\Tenant\FeatureUsageService;
use Inertia\Inertia;

class TenantController extends Controller
{
    public function __construct(protected TenantService $tenantService, protected FeatureUsageService $featureUsageService)
    {
    }

    public function index()
    {
        $tenants = $this->tenantService->getAllTenants()
            ->map(function ($tenant) {
                $owner = $tenant->users->where('type', UserType::TENANT_OWNER->value)->first();

                return [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'owner' => $owner ? [
                        'id' => $owner->id,
                        'name' => $owner->name,
                    ] : null,
                    'created_at' => $tenant->created_at->format('Y-m-d'),
                    'status' => $tenant->status,
                ];
            });

        return Inertia::render('admin/tenants/Index', [
            'tenants' => $tenants,
        ]);
    }

    public function show(Tenant $tenant)
    {
        // Mevcut verileri getir
        $owner = $tenant->users()->where('type', UserType::TENANT_OWNER->value)->first();
        $users = $tenant->users()->where('type', '!=', UserType::TENANT_OWNER->value)->get();

        // Abonelik bilgisi
        $subscription = $tenant->activeSubscription()
            ? [
                'id' => $tenant->activeSubscription()->id,
                'plan' => [
                    'name' => $tenant->activeSubscription()->plan->name,
                    'billing_period' => $tenant->activeSubscription()->billing_period,
                ],
                'price' => $tenant->activeSubscription()->price,
                'ends_at' => $tenant->activeSubscription()->ends_at?->format('Y-m-d H:i:s'),
                'next_billing_at' => $tenant->activeSubscription()->next_billing_at?->format('Y-m-d H:i:s'),
                'status' => $tenant->activeSubscription()->status->value,
                'on_trial' => $tenant->activeSubscription()->onTrial(),
            ]
            : null;

        // Kullanım bilgileri
        $usage = $this->getUsageData($tenant);

        // Son 5 aktivite
        $activities = Activity::where('tenant_id', $tenant->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'message' => $activity->message,
                    'created_at' => $activity->created_at->format('Y-m-d H:i:s'),
                ];
            });

        // Son 5 ödeme
        $payments = Payment::where('tenant_id', $tenant->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'status' => $payment->status,
                    'paid_at' => $payment->paid_at->format('Y-m-d H:i:s'),
                ];
            });

        return Inertia::render('admin/tenants/Show', [
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domain,
                'status' => $tenant->status,
                'settings' => $tenant->settings,
                'created_at' => $tenant->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $tenant->updated_at->format('Y-m-d H:i:s'),
            ],
            'subscription' => $subscription,
            'activities' => $activities,
            'payments' => $payments,
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'type' => $user->type,
                ];
            }),
            'usage' => $usage,
        ]);
    }

    protected function getUsageData(Tenant $tenant)
    {
        $featureUsage = [];
        $limitWarnings = [];

        if ($subscription = $tenant->activeSubscription()) {
            foreach ($subscription->plan->planFeatures as $planFeature) {
                $currentUsage = $planFeature->isLimited() && !$planFeature->hasUnlimitedUsage()
                    ? $this->featureUsageService->getUsageCount($tenant->id, $planFeature->feature_id, $planFeature)
                    : 0;

                $featureUsage[] = [
                    'feature_id' => $planFeature->feature_id,
                    'feature_name' => $planFeature->feature->name,
                    'current_usage' => $currentUsage,
                    'limit' => $planFeature->limit_value,
                    'is_unlimited' => $planFeature->hasUnlimitedUsage(),
                    'usage_percentage' => $planFeature->isLimited() && !$planFeature->hasUnlimitedUsage() && $planFeature->limit_value > 0
                        ? round(($currentUsage / $planFeature->limit_value) * 100, 1)
                        : null,
                ];
            }

            // Limit uyarıları
            $limitWarnings = Activity::where('tenant_id', $tenant->id)
                ->where('message', FeatureUsageService::LIMIT_THRESHOLD_MESSAGE)
                ->where('created_at', '>=', now()->subDay())
                ->latest()
                ->get()
                ->map(function ($activity) {
                    $log = $activity->log;
                    return [
                        'feature_id' => $log['feature_id'],
                        'feature_name' => $log['feature_name'],
                        'percentage' => $log['percentage'],
                    ];
                });
        }

        return [
            'featureUsage' => $featureUsage,
            'limitWarnings' => $limitWarnings,
        ];
    }

    public function show33(Tenant $tenant)
    {
        // Tenant sahibini ve kullanıcıları getir
        $owner = $tenant->users()->where('type', UserType::TENANT_OWNER->value)->first();
        $users = $tenant->users()->where('type', '!=', UserType::TENANT_OWNER->value)->get();

        // Abonelik bilgisi (SubscriptionService üzerinden)
        $subscription = $tenant->activeSubscription()
            ? [
                'id' => $tenant->activeSubscription()->id,
                'plan' => [
                    'name' => $tenant->activeSubscription()->plan->name,
                    'billing_period' => $tenant->activeSubscription()->billing_period,
                ],
                'ends_at' => $tenant->activeSubscription()->ends_at?->format('Y-m-d H:i:s'),
                'status' => $tenant->activeSubscription()->status->value,
            ]
            : null;

        // Son 5 aktivite
        $activities = Activity::where('tenant_id', $tenant->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'message' => $activity->message,
                    'created_at' => $activity->created_at->format('Y-m-d H:i:s'),
                    'log' => $activity->log,
                ];
            });

        // Son 5 ödeme
        $payments = Payment::where('tenant_id', $tenant->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'status' => $payment->status,
                    'paid_at' => $payment->paid_at->format('Y-m-d H:i:s'),
                    'billing_name' => $payment->billing_name,
                ];
            });

        return Inertia::render('admin/tenants/Show', [
            // Tenant Temel Bilgileri
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domain,
                'status' => $tenant->status,
                'settings' => $tenant->settings, // Fatura ve yetkili bilgileri burada
                'created_at' => $tenant->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $tenant->updated_at->format('Y-m-d H:i:s'),
            ],

            // Diğer Veriler
            'owner' => $owner ? [
                'id' => $owner->id,
                'name' => $owner->name,
                'email' => $owner->email,
                'phone' => $owner->phone,
            ] : null,

            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'type' => $user->type,
                    'status' => $user->status,
                ];
            }),

            'subscription' => $subscription,
            'activities' => $activities,
            'payments' => $payments,
        ]);
    }

    public function showOld(Tenant $tenant)
    {
        $owner = $this->tenantService->getTenantOwner($tenant);
        $users = $this->tenantService->getTenantUsers($tenant);

        return Inertia::render('admin/tenants/Show', [
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domain,
                'status' => $tenant->status,
                'settings' => $tenant->settings,
                'created_at' => $tenant->created_at->format('Y-m-d'),
                'updated_at' => $tenant->updated_at->format('Y-m-d'),
            ],
            'owner' => $owner ? [
                'id' => $owner->id,
                'name' => $owner->name,
                'email' => $owner->email,
            ] : null,
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'type' => $user->type,
                ];
            }),
        ]);
    }
}
