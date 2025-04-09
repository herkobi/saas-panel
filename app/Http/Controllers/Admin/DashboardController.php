<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Activity;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Temel istatistikler (Mevcut yapınıza göre)
        $stats = [
            'totalTenants' => Tenant::count(),
            'activeTenants' => Tenant::where('status', 'active')->count(),
            'newTenants' => Tenant::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
            'monthlyRevenue' => Payment::where('status', 'completed')
                ->whereBetween('paid_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->sum('amount'),
        ];

        // Tenant büyüme grafiği (Mevcut yapınız)
        $tenantGrowth = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $tenantGrowth[] = [
                'date' => $date->format('Y-m-d'),
                'count' => Tenant::whereDate('created_at', $date)->count(),
            ];
        }

        // Plan dağılımı (Subscription tablosu üzerinden)
        $planDistribution = [];
        $plans = Plan::all(); // Mevcut planlar

        foreach ($plans as $plan) {
            $planDistribution[] = [
                'name' => $plan->name,
                'count' => Subscription::where('plan_id', $plan->id)
                    ->where('status', 'active')
                    ->count(),
            ];
        }

        // Son etkinlikler (Mevcut yapınız)
        $recentActivities = Activity::with(['tenant' => function($query) {
            $query->select('id', 'name');
        }])->latest()->take(5)->get();

        return Inertia::render('admin/Dashboard', [
            'stats' => $stats,
            'tenantGrowth' => $tenantGrowth,
            'planDistribution' => $planDistribution,
            'recentActivities' => $recentActivities,
        ]);
    }
}
