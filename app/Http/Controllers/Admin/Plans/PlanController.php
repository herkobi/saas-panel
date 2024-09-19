<?php

namespace App\Http\Controllers\Admin\Plans;

use App\Http\Controllers\Controller;
use App\Services\Admin\Plan\PlanService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    protected $planService;

    public function __construct(
        PlanService $planService
    ) {
        $this->planService = $planService;
    }

    public function index(): View
    {
        $plans = $this->planService->getAllPlans();
        return view('admin.plans.index', [
            'plans' => $plans
        ]);
    }

    public function create(): View
    {
        return view('admin.plans.create');
    }
}
