<?php

namespace App\Http\Middleware;

use App\Models\Activity;
use App\Traits\AuthUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    use AuthUser;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // AuthUser trait'inden kullanıcı bilgisini al
        $this->initializeAuthUser();

        // Kullanıcı giriş yapmış ve bir tenant'a bağlıysa
        if ($this->user && $this->user->tenant_id) {
            // Tenant'ı al
            $tenant = $this->user->tenant;

            if ($tenant) {
                // Tenant'ın aktif aboneliği var mı kontrol et
                $activeSubscription = $tenant->subscriptions()
                    ->where('status', \App\Enums\SubscriptionStatus::ACTIVE->value)
                    ->where(function ($query) {
                        $query->whereNull('ends_at')
                            ->orWhere('ends_at', '>', now());
                    })
                    ->first();

                // Aktif abonelik yoksa ve şu anki route abonelik gerektirmeyen bir route değilse
                if (!$activeSubscription && !$this->isExcludedRoute($request)) {

                    // Kullanıcı tenant owner ise plan seçim sayfasına yönlendir
                    if ($this->user->isTenantOwner()) {
                        return redirect()->route('app.subscription.plans')
                            ->with('warning', 'Aktif bir aboneliğiniz bulunmamaktadır. Lütfen bir plan seçin.');
                    }

                    // Kullanıcı tenant staff ise, bilgilendirme mesajı göster
                    return redirect()->route('app.dashboard')
                        ->with('warning', 'Hesabınızın aktif bir aboneliği bulunmamaktadır. Lütfen yönetici ile iletişime geçin.');
                }
            }
        }

        return $next($request);
    }

    /**
     * İstisnai rotaları kontrol eder
     */
    private function isExcludedRoute(Request $request): bool
    {
        // Abonelik kontrolünden muaf tutulacak routelar
        $excludedRoutes = [
            'app.subscription.plans',
            'app.subscription.checkout',
            'app.subscription.process',
            'app.subscription.success',
            'app.subscription.cancel',
            'app.profile.edit',
            'app.password.edit',
            'app.appearance',
            'logout'
        ];

        return in_array($request->route()->getName(), $excludedRoutes);
    }
}
