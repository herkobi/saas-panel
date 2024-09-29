<?php

namespace App\Actions\Admin\Feature;

use App\Services\Admin\Feature\FeatureService;
use App\Events\Admin\Features\Update as Event;
use LucasDotVin\Soulbscription\Models\Feature;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): Feature
    {
        $oldFeature = $this->featureService->getFeatureById($id);
        $feature = $this->featureService->updateFeature($id, $data);
        $newFeature = $this->featureService->getFeatureById($id);
        event(new Event($feature, $this->user, $oldFeature, $newFeature));
        return $feature;
    }
}
