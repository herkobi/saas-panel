<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\ContractType;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Tenant\FeatureUsageService;
use App\Services\Tenant\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    public function __construct(protected SubscriptionService $subscriptionService, protected FeatureUsageService $featureUsageService)
    {
    }

    /**
     * Abonelik bilgisi ve kullanım detaylarını göster
     */
    public function usage()
    {
        // Kullanıcının tenant'ı
        $user = Auth::user();
        $tenant = $user->tenant;

        // Kullanıcının aktif aboneliği
        $activeSubscription = null;
        $featureUsage = [];

        if ($tenant) {
            $activeSubscription = $this->subscriptionService->getActiveSubscription($tenant);

            if ($activeSubscription) {
                // Planın özellikleri
                $planFeatures = $activeSubscription->plan->planFeatures;

                // Her bir özellik için kullanım bilgilerini hazırla
                foreach ($planFeatures as $planFeature) {
                    $feature = $planFeature->feature;
                    $isUnlimited = $planFeature->hasUnlimitedUsage();
                    $limit = $planFeature->limit_value;

                    // Eğer özellik limitliyse, kullanım miktarını getir
                    $currentUsage = 0;
                    if ($planFeature->isLimited() && !$isUnlimited) {
                        $currentUsage = $this->featureUsageService->getUsageCount($tenant->id, $feature->id, $planFeature);
                    }

                    // Kullanım yenileme tarihi
                    $resetDate = null;
                    if ($planFeature->isRenewable()) {
                        // Yenilenebilir limit için yenileme tarihi hesapla
                        switch ($planFeature->limit_reset_period) {
                            case 'hourly':
                                $resetDate = now()->addHour()->startOfHour();
                                break;
                            case 'daily':
                                $resetDate = now()->addDay()->startOfDay();
                                break;
                            case 'weekly':
                                $resetDate = now()->addWeek()->startOfWeek();
                                break;
                            case 'monthly':
                                $resetDate = now()->addMonth()->startOfMonth();
                                break;
                            case 'yearly':
                                $resetDate = now()->addYear()->startOfYear();
                                break;
                        }
                    }

                    // Kullanım geçmişi
                    $usageHistory = $this->featureUsageService->getUsageHistory(
                        $tenant->id,
                        $feature->id,
                        now()->subDays(30),
                        now()
                    );

                    // Limit bilgisi
                    $limitInfo = [
                        'is_limited' => $planFeature->isLimited(),
                        'is_access_only' => $planFeature->isAccessOnly(),
                        'is_unlimited' => $planFeature->hasUnlimitedUsage(),
                        'limit_value' => $planFeature->limit_value,
                        'limit_type' => $planFeature->limit_type,
                        'limit_reset_period' => $planFeature->limit_reset_period,
                    ];

                    $featureUsage[] = [
                        'feature_id' => $feature->id,
                        'feature_name' => $feature->name,
                        'feature_slug' => $feature->slug,
                        'description' => $feature->description,
                        'current_usage' => $currentUsage,
                        'limit_info' => $limitInfo,
                        'reset_date' => $resetDate,
                        'usage_history' => $usageHistory,
                        'usage_percentage' => $planFeature->isLimited() && !$planFeature->hasUnlimitedUsage() && $planFeature->limit_value > 0
                            ? round(($currentUsage / $planFeature->limit_value) * 100, 1)
                            : null,
                    ];
                }
            }
        }

        // Limit uyarıları
        $limitWarnings = [];
        if ($tenant) {
            // Son 24 saat içindeki limit uyarılarını getir
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
                        'feature_slug' => $log['feature_slug'] ?? '',
                        'threshold' => $log['threshold'],
                        'current_usage' => $log['current_usage'],
                        'limit' => $log['limit'],
                        'percentage' => $log['percentage'],
                    ];
                });
        }

        // Abonelik bilgileri - daha kapsamlı bilgileri ekleyelim
        $subscriptionInfo = $activeSubscription ? [
            'id' => $activeSubscription->id,
            'plan_id' => $activeSubscription->plan_id,
            'plan_name' => $activeSubscription->name,
            'billing_period' => $activeSubscription->billing_period,
            'price' => $activeSubscription->price,
            'ends_at' => $activeSubscription->ends_at,
            'next_billing_at' => $activeSubscription->next_billing_at,
            'status' => $activeSubscription->status->value,
            'status_label' => $activeSubscription->status->getLabel(),
            'trial_ends_at' => $activeSubscription->trial_ends_at,
            'on_trial' => $activeSubscription->onTrial(),
            'days_left' => $activeSubscription->daysLeft(),
            'has_pending_cancellation' => $activeSubscription->hasPendingCancellation(),
            'has_pending_plan_change' => $activeSubscription->hasPendingPlanChange(),
        ] : null;

        return Inertia::render('tenant/subscription/Usage', [
            'subscription' => $subscriptionInfo,
            'featureUsage' => $featureUsage,
            'limitWarnings' => $limitWarnings,
        ]);
    }

    /**
     * Ödeme geçmişini göster
     */
    public function billing()
    {
        // Kullanıcının tenant'ı
        $user = Auth::user();
        $tenant = $user->tenant;

        // Ödeme geçmişi
        $billingHistory = [];

        if ($tenant) {
            // Gerçek ödeme kayıtlarını getir
            $billingHistory = Payment::where('tenant_id', $tenant->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'date' => $payment->paid_at->format('Y-m-d'),
                        'amount' => number_format($payment->amount, 2) . ' ' . $payment->currency_code,
                        'status' => $payment->status,
                        'invoice_url' => null, // Fatura URL'i gelecekte eklenecek
                    ];
                });
        }

        return Inertia::render('tenant/subscription/Billing', [
            'billingHistory' => $billingHistory
        ]);
    }

    /**
     * Plan listesini göster
     */
    public function plans()
    {
        // Aktif planları al
        $plans = $this->subscriptionService->getActivePlans();

        // Kullanıcının aktif aboneliğini al (varsa)
        $activeSubscription = null;
        $user = Auth::user();
        if ($user && $user->tenant) {
            $activeSubscription = $this->subscriptionService->getActiveSubscription($user->tenant);
        }

        return Inertia::render('tenant/subscription/Plans', [
            'plans' => $plans->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'description' => $plan->description,
                    'is_featured' => $plan->is_featured,
                    'is_free' => $plan->is_free,
                    'billing_period' => $plan->billing_period,
                    'monthly_price' => $plan->monthly_price,
                    'yearly_price' => $plan->yearly_price,
                    'trial_days' => $plan->trial_days,
                    'formatted_monthly_price' => $plan->formatted_monthly_price,
                    'formatted_yearly_price' => $plan->formatted_yearly_price,
                    'features' => $plan->planFeatures->map(function ($planFeature) {
                        return [
                            'id' => $planFeature->id,
                            'feature_name' => $planFeature->feature->name,
                            'feature_description' => $planFeature->feature->description,
                            'access_type' => $planFeature->access_type,
                            'limit_type' => $planFeature->limit_type,
                            'limit_value' => $planFeature->limit_value,
                            'limit_reset_period' => $planFeature->limit_reset_period,
                            'formatted_limit' => $planFeature->formatted_limit,
                        ];
                    }),
                ];
            }),
            'activeSubscription' => $activeSubscription ? [
                'id' => $activeSubscription->id,
                'plan_id' => $activeSubscription->plan_id,
                'plan_name' => $activeSubscription->name,
                'billing_period' => $activeSubscription->billing_period,
                'price' => $activeSubscription->price,
                'trial_ends_at' => $activeSubscription->trial_ends_at,
                'ends_at' => $activeSubscription->ends_at,
                'next_billing_at' => $activeSubscription->next_billing_at,
                'status' => $activeSubscription->status->value,
                'status_label' => $activeSubscription->status->getLabel(),
                'on_trial' => $activeSubscription->onTrial(),
                'is_active' => $activeSubscription->isActive(),
                'days_left' => $activeSubscription->daysLeft(),
                'has_pending_cancellation' => $activeSubscription->hasPendingCancellation(),
                'has_pending_plan_change' => $activeSubscription->hasPendingPlanChange(),
            ] : null,
        ]);
    }

    /**
     * Ödeme sayfasını göster
     */
    public function checkout(Request $request, Plan $plan)
    {
        // Seçilen faturalama periyodu
        $billingPeriod = $request->get('billing_period', 'monthly');

        // Plan fiyatı
        $price = $billingPeriod === 'yearly' ? $plan->yearly_price : $plan->monthly_price;

        // Vergi hesaplamaları
        $taxRate = 0; // Varsayılan değer

        // Config'den vergi oranını al
        $taxRates = config('tenant.tax_rates', []);
        $taxRateConfig = collect($taxRates)->firstWhere('code', $plan->tax_rate_code);
        if ($taxRateConfig) {
            $taxRate = $taxRateConfig['rate'];
        }

        $taxAmount = $price * ($taxRate / 100);
        $totalAmount = $price + $taxAmount;

        // Formatlanmış fiyatlar
        $formattedPrice = $plan->getFormattedPrice($price);
        $formattedTaxAmount = $plan->getFormattedPrice($taxAmount);
        $formattedTotalAmount = $plan->getFormattedPrice($totalAmount);

        // Ödeme türündeki aktif sözleşmeleri getir
        $paymentContracts = Contract::where('type', ContractType::PAYMENT->value)
            ->where('status', true)
            ->get();

        // Tenant'ın fatura bilgilerini al (eğer varsa)
        $billingInfo = [];
        $user = Auth::user();
        if ($user && $user->tenant) {
            $tenant = $user->tenant;
            $settings = $tenant->settings ?: [];

            if (isset($settings['billing'])) {
                $billingInfo = $settings['billing'];
            }
        }

        return Inertia::render('tenant/subscription/Checkout', [
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'description' => $plan->description,
                'is_free' => $plan->is_free,
                'billing_period' => $billingPeriod,
                'price' => $price,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'formatted_price' => $formattedPrice,
                'formatted_tax_amount' => $formattedTaxAmount,
                'formatted_total_amount' => $formattedTotalAmount,
                'trial_days' => $plan->trial_days,
            ],
            'paymentContracts' => $paymentContracts,
            'billingInfo' => $billingInfo,
        ]);
    }

    /**
     * Abonelik işlemini gerçekleştir
     */
    public function process(Request $request, Plan $plan)
    {
        $validatedData = $request->validate([
            'billing_period' => 'required|in:monthly,yearly',
            'billing_name' => 'required|string|max:255',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_district' => 'required|string',
            'billing_postal_code' => 'required|string',
            'billing_tax_office' => 'nullable|string',
            'billing_tax_number' => 'nullable|string',
            'billing_email' => 'required|email',
            'billing_contact_name' => 'required|string|max:255',
            'billing_phone' => 'required|string|max:20',
            'contracts' => 'required|array',
            'contracts.*' => 'required|exists:contracts,id',
        ]);

        $user = Auth::user();

        if (!$user->tenant_id) {
            return redirect()->route('app.dashboard')
                ->with('error', 'Tenant bilgisi bulunamadı.');
        }

        // Tenant ilişkisini yükleyelim
        $tenant = $user->tenant;

        if (!$tenant) {
            return redirect()->route('app.dashboard')
                ->with('error', 'Tenant bilgisi bulunamadı.');
        }

        // Fatura bilgilerini tenant ayarlarına kaydet
        $settings = $tenant->settings ?: [];
        $settings['billing'] = [
            'name' => $validatedData['billing_name'],
            'address' => $validatedData['billing_address'],
            'city' => $validatedData['billing_city'],
            'district' => $validatedData['billing_district'],
            'postal_code' => $validatedData['billing_postal_code'],
            'tax_office' => $validatedData['billing_tax_office'] ?? '',
            'tax_number' => $validatedData['billing_tax_number'] ?? '',
            'email' => $validatedData['billing_email'],
            'contact_name' => $validatedData['billing_contact_name'],
            'phone' => $validatedData['billing_phone'],
        ];

        $tenant->settings = $settings;
        $tenant->save();

        try {
            // Aboneliği oluştur
            $subscription = $this->subscriptionService->createSubscription($tenant, $plan, $validatedData);

            return redirect()->route('app.subscription.success')
                ->with('success', 'Aboneliğiniz başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            Log::error('Abonelik oluştururken hata oluştu', [
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('app.subscription.plans')
                ->with('error', 'Abonelik oluşturulurken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }

    /**
     * Abonelik işlemi başarılı sayfası
     */
    public function success()
    {
        return Inertia::render('tenant/subscription/Success');
    }

    /**
     * Plan değiştirme sayfasını göster
     */
    public function changePlan(Request $request)
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        if (!$tenant) {
            return redirect()->route('app.dashboard')
                ->with('error', 'Tenant bilgisi bulunamadı.');
        }

        // Aktif aboneliği al
        $activeSubscription = $this->subscriptionService->getActiveSubscription($tenant);
        if (!$activeSubscription) {
            return redirect()->route('app.subscription.plans')
                ->with('error', 'Aktif bir aboneliğiniz bulunmamaktadır.');
        }

        // Tüm aktif planları getir
        $plans = $this->subscriptionService->getActivePlans()
            ->filter(function ($plan) use ($activeSubscription) {
                // Mevcut plan hariç diğer planları göster
                return $plan->id !== $activeSubscription->plan_id;
            })
            ->map(function ($plan) use ($activeSubscription) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'description' => $plan->description,
                    'is_featured' => $plan->is_featured,
                    'is_free' => $plan->is_free,
                    'billing_period' => $plan->billing_period,
                    'monthly_price' => $plan->monthly_price,
                    'yearly_price' => $plan->yearly_price,
                    'trial_days' => $plan->trial_days,
                    'formatted_monthly_price' => $plan->formatted_monthly_price,
                    'formatted_yearly_price' => $plan->formatted_yearly_price,
                    'is_upgrade' => $plan->monthly_price > $activeSubscription->plan->monthly_price,
                    'features' => $plan->planFeatures->map(function ($planFeature) {
                        return [
                            'id' => $planFeature->id,
                            'feature_name' => $planFeature->feature->name,
                            'feature_description' => $planFeature->feature->description,
                            'access_type' => $planFeature->access_type,
                            'limit_type' => $planFeature->limit_type,
                            'limit_value' => $planFeature->limit_value,
                            'limit_reset_period' => $planFeature->limit_reset_period,
                            'formatted_limit' => $planFeature->formatted_limit,
                        ];
                    }),
                ];
            });

        return Inertia::render('tenant/subscription/ChangePlan', [
            'plans' => $plans,
            'currentSubscription' => [
                'id' => $activeSubscription->id,
                'plan_id' => $activeSubscription->plan_id,
                'plan_name' => $activeSubscription->plan->name,
                'billing_period' => $activeSubscription->billing_period,
                'price' => $activeSubscription->price,
                'ends_at' => $activeSubscription->ends_at,
                'days_left' => $activeSubscription->daysLeft(),
                'formatted_price' => $activeSubscription->plan->getFormattedPrice(
                    $activeSubscription->billing_period === 'monthly'
                        ? $activeSubscription->plan->monthly_price
                        : $activeSubscription->plan->yearly_price
                ),
            ],
        ]);
    }

    /**
     * Plan değiştirme seçeneklerini göster
     */
    public function changeOptions(Request $request, Plan $plan)
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        if (!$tenant) {
            return redirect()->route('app.dashboard')
                ->with('error', 'Tenant bilgisi bulunamadı.');
        }

        // Aktif aboneliği al
        $activeSubscription = $this->subscriptionService->getActiveSubscription($tenant);
        if (!$activeSubscription) {
            return redirect()->route('app.subscription.plans')
                ->with('error', 'Aktif bir aboneliğiniz bulunmamaktadır.');
        }

        // Billing period seçimi
        $billingPeriod = $request->get('billing_period', $activeSubscription->billing_period);

        // Pro-rata hesaplama (sadece yükseltme için, düşürme her zaman dönem sonunda)
        $proratedAmounts = null;
        $isUpgrade = false;

        // Yeni plan daha pahalıysa (yükseltme) pro-rata hesapla
        $currentPrice = $activeSubscription->billing_period === 'monthly'
            ? $activeSubscription->plan->monthly_price
            : $activeSubscription->plan->yearly_price;

        $newPrice = $billingPeriod === 'monthly'
            ? $plan->monthly_price
            : $plan->yearly_price;

        $isUpgrade = $newPrice > $currentPrice;

        if ($isUpgrade) {
            $proratedAmounts = $this->subscriptionService->calculateProratedAmount($activeSubscription, $plan);
        }

        return Inertia::render('tenant/subscription/ChangeOptions', [
            'currentSubscription' => [
                'id' => $activeSubscription->id,
                'plan_name' => $activeSubscription->plan->name,
                'billing_period' => $activeSubscription->billing_period,
                'ends_at' => $activeSubscription->ends_at ? $activeSubscription->ends_at->format('Y-m-d') : null,
                'days_left' => $activeSubscription->daysLeft(),
            ],
            'newPlan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'billing_period' => $billingPeriod,
                'price' => $billingPeriod === 'monthly' ? $plan->monthly_price : $plan->yearly_price,
                'formatted_price' => $billingPeriod === 'monthly'
                    ? $plan->formatted_monthly_price
                    : $plan->formatted_yearly_price,
            ],
            'proratedAmounts' => $proratedAmounts ? [
                'prorated_amount' => $plan->getFormattedPrice($proratedAmounts['amount_to_charge']),
                'tax_amount' => $plan->getFormattedPrice($proratedAmounts['tax_amount']),
                'total_amount' => $plan->getFormattedPrice($proratedAmounts['total_amount']),
                'days_left' => $proratedAmounts['days_left'],
            ] : null,
            'isUpgrade' => $isUpgrade,
            'billingPeriod' => $billingPeriod,
        ]);
    }

    /**
     * Plan değiştirme işlemini gerçekleştir
     */
    public function processChange(Request $request, Plan $plan)
    {
        $validatedData = $request->validate([
            'change_type' => ['required', 'in:now,end_of_period'],
            'billing_period' => ['required', 'in:monthly,yearly'],
        ]);

        $user = Auth::user();
        $tenant = $user->tenant;

        if (!$tenant) {
            return redirect()->route('app.dashboard')
                ->with('error', 'Tenant bilgisi bulunamadı.');
        }

        // Aktif aboneliği al
        $activeSubscription = $this->subscriptionService->getActiveSubscription($tenant);
        if (!$activeSubscription) {
            return redirect()->route('app.subscription.plans')
                ->with('error', 'Aktif bir aboneliğiniz bulunmamaktadır.');
        }

        // Seçilen değiştirme türüne göre işlem yap
        $billingPeriod = $validatedData['billing_period'];

        try {
            if ($validatedData['change_type'] === 'now') {
                // Hemen değiştir - Yeni plan daha pahalıysa pro-rata ödeme gerekir
                $this->subscriptionService->changePlanImmediately(
                    $activeSubscription,
                    $plan,
                    $billingPeriod
                );

                return redirect()->route('app.subscription.plans')
                    ->with('success', 'Abonelik planınız başarıyla değiştirildi.');
            } else {
                // Dönem sonunda değiştir
                $this->subscriptionService->schedulePlanChange(
                    $activeSubscription,
                    $plan,
                    $billingPeriod
                );

                return redirect()->route('app.subscription.plans')
                    ->with('success', 'Abonelik plan değişikliğiniz dönem sonuna planlandı.');
            }
        } catch (\Exception $e) {
            Log::error('Plan değiştirme hatası', [
                'tenant_id' => $tenant->id,
                'subscription_id' => $activeSubscription->id,
                'new_plan_id' => $plan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('app.subscription.plans')
                ->with('error', 'Plan değiştirme işlemi sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }

    /**
     * Abonelik iptal seçeneklerini göster
     */
    public function cancelOptions(Subscription $subscription)
    {
        $user = Auth::user();

        // İzin kontrolü - sadece tenant owner iptal edebilir
        if (!$user->type || !$user->type->isTenantOwner() || $user->tenant_id !== $subscription->tenant_id) {
            return redirect()->route('app.dashboard')
                ->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }

        return Inertia::render('tenant/subscription/CancelOptions', [
            'subscription' => [
                'id' => $subscription->id,
                'plan_name' => $subscription->plan->name,
                'billing_period' => $subscription->billing_period,
                'ends_at' => $subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : null,
                'days_left' => $subscription->daysLeft(),
            ],
        ]);
    }

    /**
     * Aboneliği iptal et
     */
    public function cancel(Request $request, Subscription $subscription)
    {
        $user = Auth::user();

        // İzin kontrolü - sadece tenant owner iptal edebilir
        if (!$user->type || !$user->type->isTenantOwner() || $user->tenant_id !== $subscription->tenant_id) {
            return redirect()->route('app.dashboard')
                ->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }

        $validatedData = $request->validate([
            'cancel_type' => ['required', 'in:now,end_of_period'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            if ($validatedData['cancel_type'] === 'now') {
                // Hemen iptal et
                $this->subscriptionService->cancelSubscription($subscription);
                $message = 'Aboneliğiniz başarıyla iptal edildi.';
            } else {
                // Dönem sonunda iptal et
                $this->subscriptionService->scheduleCancellation($subscription, $validatedData['reason'] ?? null);
                $message = 'Aboneliğiniz dönem sonunda iptal edilecek şekilde ayarlandı.';
            }

            return redirect()->route('app.subscription.plans')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Abonelik iptal hatası', [
                'tenant_id' => $user->tenant_id,
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('app.subscription.plans')
                ->with('error', 'Abonelik iptal işlemi sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }
}
