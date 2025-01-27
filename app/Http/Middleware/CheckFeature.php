<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\User\FeatureService;
use Illuminate\Http\Request;

class CheckFeature
{
    protected $featureManager;

    public function __construct(FeatureService $featureManager)
    {
        $this->featureManager = $featureManager;
    }

    public function handle(Request $request, Closure $next, string $featureName)
    {
        if (!$this->featureManager->checkFeatureAvailability($featureName)) {
            return response()->json([
                'message' => 'Bu özelliği kullanma yetkiniz bulunmuyor.'
            ], 403);
        }

        return $next($request);
    }
}
