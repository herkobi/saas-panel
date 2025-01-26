<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Order;
use App\Traits\AuthUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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

   public function __construct() {
       $this->initializeAuthUser();
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

        if ($this->user->tenant->new_tenant && $this->user->tenant->first_paid_plan) {
            return redirect()->route('app.account.payment.create', $this->user->tenant->first_paid_plan);
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
