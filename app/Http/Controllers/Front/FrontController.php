<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Admin\Plan\PlanService;
use App\Services\Admin\Settings\AgreementService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontController extends Controller
{

    protected $agreementService;
    protected $planService;

    public function __construct(
        AgreementService $agreementService,
        PlanService $planService,
    ) {
        $this->agreementService = $agreementService;
        $this->planService = $planService;
    }

    public function index(): View
    {
        $plans = $this->planService->getFrontPlans();
        return view('front.index', [
            'plans' => $plans
        ]);
    }

    public function agreement(string $slug): View
    {
        $agreement = $this->agreementService->getAgreementBySlug($slug);
        return view('front.agreement', [
            'agreement' => $agreement
        ]);
    }
}
