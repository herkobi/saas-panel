<?php

namespace App\Http\Middleware;

use App\Models\Order;
use App\Services\Admin\Tools\OrderstatusService;
use App\Services\OrderService;
use App\Traits\AuthUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use LucasDotVin\Soulbscription\Models\Scopes\SuppressingScope;

class SubscriptionCheck
{
    use AuthUser;

    protected array $allowedRoutes = [
        'app.country.states',  // Ödeme formunda ülke/eyalet seçimi için
        'app.taxes',          // Vergi hesaplaması için
        'app.account.payment.create',  // Ödeme sayfası
        'app.account.payment.store',   // Ödeme işlemi
        'app.account.payment.bacs-success', // Banka havalesi başarılı sayfası
        'app.account.payment.upload',   // Dekont yükleme
        'app.account.plans',           // Planlar sayfası
        'app.account.*',               // Account ile başlayan tüm rotalar
    ];

    protected $orderService;
    protected $orderstatusService;

    public function __construct(
        OrderService $orderService,
        OrderstatusService $orderstatusService
    ) {
        $this->initializeAuthUser();
        $this->orderService = $orderService;
        $this->orderstatusService = $orderstatusService;
    }

    protected function isAllowedRoute(?string $currentRoute): bool
    {
        if (!$currentRoute) {
            return false;
        }

        return collect($this->allowedRoutes)->some(
            fn ($pattern) => Str::is($pattern, $currentRoute)
        );
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $this->user;
        $currentRoute = $request->route()?->getName();

        // İzin verilen rotalar için devam et
        if ($this->isAllowedRoute($currentRoute)) {
            return $next($request);
        }

        // SuppressingScope olmadan aboneliği sorgula
        $subscription = $user->subscription()
            ->withoutGlobalScope(SuppressingScope::class)
            ->first();

        // Hiç abonelik yoksa planlara yönlendir
        if (!$subscription) {
            return redirect()
                ->route('app.account.plans')
                ->with('warning', 'Lütfen bir abonelik planı seçin.');
        }

        // Askıya alınmış abonelik varsa ödeme sayfasına yönlendir
        if ($subscription->suppressed_at) {
            return redirect()
                ->route('app.account.payment.create', ['plan_id' => $subscription->plan_id])
                ->with('warning', 'Lütfen ödeme işleminizi tamamlayın.');
        }

        // Abonelik aktif ama süresi 7 gün veya daha az kaldıysa ödeme kaydı oluştur
        if ($subscription->expired_at && $subscription->expired_at->diffInDays(now()) <= 7 && $subscription->plan->price > 0) {
            $hasPendingOrder = Order::query()
                ->where('user_id', $user->id)
                ->where('plan_id', $subscription->plan_id)
                ->whereHas('orderstatus', fn ($q) => $q->where('code', 'PENDING_PAYMENT'))
                ->exists();

            if (!$hasPendingOrder) {
                $orderData = [
                    'user_id' => $user->id,
                    'tenant_id' => $user->tenant_id,
                    'plan_id' => $subscription->plan_id,
                    'currency_id' => $subscription->plan->currency_id,
                    'amount' => $subscription->plan->price,
                    'payment_type' => 'bank',
                    'invoice_data' => [
                        'invoice_name' => $user->account->invoice_name,
                        'tax_number' => $user->account->tax_number,
                        'tax_office' => $user->account->tax_office,
                        'address' => $user->account->address,
                        'zip_code' => $user->account->zip_code,
                        'country_id' => $user->account->country_id,
                        'state_id' => $user->account->state_id,
                    ],
                    'notes' => 'Otomatik oluşturulan yenileme ödemesi',
                    'orderstatus_id' => $this->orderstatusService->getOrderstatusByCode('PENDING_PAYMENT')->id,
                ];

                $order = $this->orderService->createPaymentOrder($orderData);
            }
        }

        // Bekleyen ödeme kontrolü
        $pendingOrder = Order::query()
            ->where('user_id', $user->id)
            ->where('plan_id', $subscription->plan_id)
            ->whereHas('orderstatus', fn ($q) => $q->where('code', 'PENDING_PAYMENT'))
            ->first();

        if ($pendingOrder) {
            return redirect()
                ->route('app.account.payment.create', ['plan_id' => $subscription->plan_id])
                ->with('warning', 'Bekleyen ödemenizi tamamlayın.');
        }

        return $next($request);
    }
}
