<?php

namespace App\Actions\Admin\Feature;

use App\Models\Feature;
use App\Services\Admin\Feature\FeatureService;
use App\Events\Admin\Features\Create as Event;
use App\Traits\AuthUser;

class Create
{
    use AuthUser;

    protected $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): Feature
    {
        $feature = $this->featureService->createFeature($data);
        event(new Event($feature, $this->user));
        return $feature;
    }
}
