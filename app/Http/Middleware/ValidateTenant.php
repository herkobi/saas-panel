<?php

namespace App\Http\Middleware;

use App\Models\Link;
use App\Traits\AuthUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateTenant
{
    use AuthUser;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->initializeAuthUser();

        // Kullanıcı giriş yapmış ve tenant'a sahipse
        if ($this->user && $this->user->tenant_id) {
            $alias = $request->route('id');

            // Preview veya benzeri istekler için tenant kontrolü yap
            if ($request->has('preview') || $request->has('admin') || $request->is('*/edit') || $request->is('*/stats')) {
                $link = Link::where('alias', $alias)->first();

                // Link mevcut değilse veya kullanıcının tenant'ına ait değilse
                if (!$link || $link->tenant_id !== $this->user->tenant_id) {
                    // Admin ise tüm linklere erişim sağlayabilir
                    if ($this->user->isAdmin()) {
                        return $next($request);
                    }

                    // Link yoksa 404, varsa 403 döndür
                    abort(!$link ? 404 : 403, 'Bu linke erişim yetkiniz bulunmamaktadır.');
                }
            }
        }

        return $next($request);
    }
}
