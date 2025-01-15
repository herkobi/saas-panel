<?php

namespace App\Actions\Admin\Tools\State;

use App\Services\Admin\Tools\StateService;
use App\Events\Admin\Tools\State\Update as Event;
use App\Models\State;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $stateService;

    public function __construct(StateService $stateService)
    {
        $this->stateService = $stateService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): State
    {
        $oldState = $this->stateService->getStateById($id);
        $state = $this->stateService->updateState($id, $data);
        $newState = $this->stateService->getStateById($id);
        event(new Event($state, $this->user, $oldState, $newState));
        return $state;
    }
}
