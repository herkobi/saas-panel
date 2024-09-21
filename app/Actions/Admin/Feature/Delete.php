<?php

namespace App\Actions\Admin\Feature;

use App\Services\Admin\Feature\FeatureService;
use App\Events\Admin\Features\Delete as Event;
use App\Models\Feature;
use App\Traits\AuthUser;

class Delete
{
    use AuthUser;

    protected $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
        $this->initializeAuthUser();
    }

    public function execute(string $id): Feature
    {
        $feature = $this->featureService->getFeatureById($id);
        $this->featureService->deleteFeature($id);
        event(new Event($feature, $this->user));
        return $feature;
    }
}
