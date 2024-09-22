<?php

namespace App\Http\Controllers\Admin\Plans;

use App\Http\Controllers\Controller;
use App\Services\Admin\Feature\FeatureService;
use App\Services\Admin\Plan\PlanService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    protected $planService;
    protected $featureService;

    public function __construct(
        PlanService $planService,
        FeatureService $featureService,
    ) {
        $this->planService = $planService;
        $this->featureService = $featureService;
    }

    public function index(): View
    {
        return view('admin.plans.index');
    }

    public function create(): View
    {
        $features = $this->featureService->getAllFeatures();
        return view('admin.plans.create', [
            'features' => $features
        ]);
    }
}
