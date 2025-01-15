<?php

namespace App\Http\Controllers\User;

use App\Actions\Admin\Settings\Agreement\Accept;
use App\Services\Admin\Tools\StateService;
use App\Enums\AgreementVersionStatus;
use App\Enums\Status;
use App\Facades\Setting;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Agreement\AgreementAcceptRequest;
use App\Models\Agreement;
use App\Models\Tax;
use App\Models\User;
use App\Services\Admin\Tools\TaxService;
use App\Traits\AuthUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use AuthUser;

    protected $accept;
    protected $stateService;
    protected $taxService;

    public function __construct(
        Accept $accept,
        StateService $stateService,
        TaxService $taxService,
    ) {
        $this->initializeAuthUser();
        $this->accept = $accept;
        $this->stateService = $stateService;
        $this->taxService = $taxService;
    }

    public function index(): View
    {
        return view('user.index');
    }

    public function passive(User $user): View
    {
        return view('layouts.passive', [
            'user' => $user
        ]);
    }

    public function sign(): View
    {
        $agreements = Agreement::where('user_type', $this->user->type)
            ->where('status', Status::ACTIVE)
            ->whereHas('versions', function($query) {
                $query->where('status', AgreementVersionStatus::PUBLISHED);
            })
            ->with(['versions' => function($query) {
                $query->where('status', AgreementVersionStatus::PUBLISHED)
                     ->latest('published_at');
            }])
            ->get()
            ->filter(function($agreement) {
                $latestVersion = $agreement->latestVersion();
                return $latestVersion && !$this->user->hasAcceptedVersion($latestVersion);
            });

        return view('user.sign', [
            'agreements' => $agreements
        ]);
    }

    public function accept(AgreementAcceptRequest $request): RedirectResponse
    {
        $agreement = $this->accept->execute($request->validated());
        return redirect()->back()
            ->with('success', $agreement->title . ' başarıyla kabul edildi.');
    }

    public function getStates(Request $request): JsonResponse
    {
        $states = $this->stateService->getCountryStates($request->country_id);
        return response()->json($states);
    }

    public function getTaxes(Request $request): JsonResponse
    {
        // country_id kontrolü
        if (!$request->country_id) {
            return response()->json([
                'taxes' => [],
                'tax_total' => 0
            ]);
        }

        try {
            $taxes = $this->taxService->getTaxesByRegion(
                $request->country_id,
                $request->state_id
            );

            // Eğer hiç vergi bulunamadıysa ve varsayılan vergi tanımlıysa
            if ($taxes->isEmpty() && Setting::get('tax')) {
                $defaultTax = Tax::where('code', Setting::get('tax'))
                                ->where('status', Status::ACTIVE)
                                ->first();
                if ($defaultTax) {
                    $taxes = collect([$defaultTax]);
                }
            }

            $taxInfo = $taxes->map(function($tax) {
                return [
                    'code' => $tax->code,
                    'value' => (float)$tax->value
                ];
            });

            return response()->json([
                'taxes' => $taxInfo,
                'tax_total' => $taxInfo->sum('value') // toplam vergi oranı
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
