<?php

namespace App\Http\Middleware;

use App\Models\Order;
use App\Services\Admin\Tools\OrderstatusService;
use App\Services\OrderService;
use App\Traits\AuthUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionCheck
{
   use AuthUser;

   protected array $allowedRoutes = [
       'app.country.states',  // Ödeme formunda ülke/eyalet seçimi için
       'app.taxes',          // Vergi hesaplaması için
       'app.account.payments',
       'app.account.payment.show',
       'app.account.payment.create',  // Ödeme sayfası
       'app.account.payment.store',   // Ödeme işlemi
       'app.account.payment.bacs-success', // Banka havalesi başarılı sayfası
       'app.account.payment.upload',   // Dekont yükleme
       'app.account.plans',           // Planlar sayfası
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
       $currentRoute = $request->route()?->getName();

       // İzin verilen rotalara sadece tenant owner erişebilir
       if ($this->isAllowedRoute($currentRoute)) {
           if (!$this->user->is_tenant_owner) {
               return redirect()
                   ->route('app.account.payment.normaluser')
                   ->with('warning', 'Ödeme işlemleri sadece hesap yöneticisi tarafından yapılabilir.');
           }
           return $next($request);
       }

       // Aktif subscription kontrolü
       $subscription = $this->user->tenant->subscription;

       if (!$subscription || $subscription->expired()) {
           if ($this->user->is_tenant_owner) {
               return redirect()
                   ->route('app.account.plans')
                   ->with('warning', 'Aboneliğiniz sona ermiştir. Lütfen yeni bir plan seçin.');
           }

           return redirect()
               ->route('app.account.payment.normaluser')
               ->with('warning', 'Aboneliğiniz sona ermiştir. Lütfen hesap yöneticiniz ile iletişime geçin.');
       }

       // Abonelik aktif ama süresi 7 gün veya daha az kaldıysa ödeme kaydı oluştur
       if ($subscription->plan->price > 0 && $subscription->expired_at->diffInDays(now()) <= 7) {
           $hasPendingOrder = Order::query()
               ->where('tenant_id', $this->user->tenant_id)
               ->where('plan_id', $subscription->plan_id)
               ->whereHas('orderstatus', fn ($q) => $q->where('code', 'PENDING_PAYMENT'))
               ->exists();

           if (!$hasPendingOrder) {
               $orderData = [
                   'user_id' => $this->user->id,
                   'tenant_id' => $this->user->tenant_id,
                   'plan_id' => $subscription->plan_id,
                   'currency_id' => $subscription->plan->currency_id,
                   'amount' => $subscription->plan->price,
                   'payment_type' => 'bank',
                   'invoice_data' => [
                       'invoice_name' => $this->user->account->invoice_name,
                       'tax_number' => $this->user->account->tax_number,
                       'tax_office' => $this->user->account->tax_office,
                       'address' => $this->user->account->address,
                       'zip_code' => $this->user->account->zip_code,
                       'country_id' => $this->user->account->country_id,
                       'state_id' => $this->user->account->state_id,
                   ],
                   'notes' => 'Otomatik oluşturulan yenileme ödemesi',
                   'orderstatus_id' => $this->orderstatusService->getOrderstatusByCode('PENDING_PAYMENT')->id,
               ];

               $this->orderService->createPaymentOrder($orderData);
           }
       }

       // Bekleyen ödeme varsa ve tenant owner ise ödeme sayfasına yönlendir
       if ($this->user->is_tenant_owner) {
           $pendingOrder = Order::query()
               ->where('tenant_id', $this->user->tenant_id)
               ->where('plan_id', $subscription->plan_id)
               ->whereHas('orderstatus', fn ($q) => $q->where('code', 'PENDING_PAYMENT'))
               ->first();

           if ($pendingOrder) {
               return redirect()
                   ->route('app.account.payment.create', $subscription->plan_id)
                   ->with('warning', 'Bekleyen ödemenizi tamamlayın.');
           }
       }

       return $next($request);
   }
}
