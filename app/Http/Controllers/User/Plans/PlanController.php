<?php

namespace App\Http\Controllers\User\Plans;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Admin\Currency;
use App\Models\Admin\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str;
use LucasDotVin\Soulbscription\Models\Subscription;

class PlanController extends Controller
{
    public function index() : View
    {
        return view('user.plans.index');
    }
}
