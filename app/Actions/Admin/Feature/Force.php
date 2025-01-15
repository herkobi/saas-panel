<?php

namespace App\Actions\Admin\Feature;

use App\Services\Admin\Feature\FeatureService;
use App\Events\Admin\Features\Force as Event;
use LucasDotVin\Soulbscription\Models\Feature;
use App\Traits\AuthUser;

class Force
{
    use AuthUser;

    protected $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
        $this->initializeAuthUser();
    }

    public function execute(int $id): Feature
    {
        $feature = $this->featureService->getFeatureById($id, true);
        $this->featureService->forceDelete($id);
        event(new Event($feature, $this->user));
        return $feature;
    }
}
