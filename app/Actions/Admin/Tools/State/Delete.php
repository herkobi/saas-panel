<?php

namespace App\Actions\Admin\Tools\State;

use App\Services\Admin\Tools\StateService;
use App\Events\Admin\Tools\State\Delete as Event;
use App\Models\State;
use App\Traits\AuthUser;

class Delete
{
    use AuthUser;

    protected $stateService;

    public function __construct(StateService $stateService)
    {
        $this->stateService = $stateService;
        $this->initializeAuthUser();
    }

    public function execute(string $id): State
    {
        $state = $this->stateService->getStateById($id);
        $this->stateService->deleteState($id);
        event(new Event($state, $this->user));
        return $state;
    }
}
