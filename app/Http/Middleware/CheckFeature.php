<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\User\FeatureService;
use Illuminate\Http\Request;
use OutOfBoundsException;
use OverflowException;

class CheckFeature
{
    protected $featureManager;

    public function __construct(FeatureService $featureManager)
    {
        $this->featureManager = $featureManager;
    }

    public function handle(Request $request, Closure $next, string $featureName)
    {
        try {
            if (!$this->featureManager->checkFeatureAvailability($featureName)) {
                return response()->json([
                    'message' => 'Bu özelliği kullanma yetkiniz bulunmuyor.'
                ], 403);
            }

            return $next($request);
        } catch (OutOfBoundsException $e) {
            return response()->json([
                'message' => 'Bu özellik planınızda bulunmuyor.'
            ], 403);
        } catch (OverflowException $e) {
            return response()->json([
                'message' => 'Bu özellik için kullanım limitiniz dolmuş.'
            ], 403);
        }
    }
}
