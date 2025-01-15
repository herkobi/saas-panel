<?php

namespace App\Http\Middleware;

use App\Enums\AgreementVersionStatus;
use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Agreement;
use App\Traits\AuthUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgreementCheck
{
    use AuthUser;

    public function __construct()
    {
        $this->initializeAuthUser();
    }

    public function handle(Request $request, Closure $next)
    {
        // Admin panelde isek ve Admin tipinde sözleşme yoksa geç
        if ($request->routeIs('panel.*') && $this->user->type === UserType::ADMIN) {
            $hasAdminAgreements = Agreement::where('user_type', UserType::ADMIN)
                ->where('status', Status::ACTIVE)
                ->whereHas('versions', function($query) {
                    $query->where('status', AgreementVersionStatus::PUBLISHED);
                })
                ->exists();

            if (!$hasAdminAgreements) {
                return $next($request);
            }
        }

        // Kullanıcı panelde isek ve User tipinde sözleşme yoksa geç
        if ($request->routeIs('app.*') && $this->user->type === UserType::USER) {
            $hasUserAgreements = Agreement::where('user_type', UserType::USER)
                ->where('status', Status::ACTIVE)
                ->whereHas('versions', function($query) {
                    $query->where('status', AgreementVersionStatus::PUBLISHED);
                })
                ->exists();

            if (!$hasUserAgreements) {
                return $next($request);
            }
        }

        // Zaten sözleşme imzalama sayfasındaysa geç
        if ($request->routeIs('app.agreement.sign') || $request->routeIs('panel.settings.agreement.sign')) {
            return $next($request);
        }

        // İlgili kullanıcı tipine göre aktif sözleşmeleri al
        $agreements = Agreement::where('user_type', $this->user->type)
            ->where('status', Status::ACTIVE)
            ->whereHas('versions', function($query) {
                $query->where('status', AgreementVersionStatus::PUBLISHED);
            })
            ->with([
                'versions' => function($query) {
                    $query->where('status', AgreementVersionStatus::PUBLISHED)
                         ->latest('published_at');
                }
            ])
            ->get();

        // Hiç sözleşme yoksa geç
        if($agreements->isEmpty()) {
            return $next($request);
        }

        // Kullanıcının imzaladığı sözleşmeler
        $userAgreements = $this->user->agreements;

        foreach ($agreements as $agreement) {
            $latestVersion = $agreement->versions->first();

            if (!$latestVersion) continue;

            // Kullanıcı bu versiyonu imzalamış mı?
            $hasAccepted = $userAgreements->contains(function ($userAgreement) use ($latestVersion) {
                return $userAgreement->pivot->agreement_version_id === $latestVersion->id;
            });

            if (!$hasAccepted) {
                if ($latestVersion->block_access) {
                    // Admin ve User için farklı route'lar
                    $redirectRoute = $this->user->type === UserType::ADMIN
                        ? 'panel.settings.agreement.sign'
                        : 'app.agreement.sign';

                    return redirect()->route($redirectRoute)
                        ->with('warning', 'Sisteme devam edebilmek için güncel sözleşmeleri kabul etmeniz gerekmektedir.');
                }

                if ($latestVersion->require_acceptance) {
                    return redirect()->back()
                        ->with('warning', 'Kabul etmeniz gereken güncel sözleşmeler bulunmaktadır. En kısa sürede sözleşmeleri inceleyip onaylamanız gerekmektedir.');
                }
            }
        }

        return $next($request);
    }
}
