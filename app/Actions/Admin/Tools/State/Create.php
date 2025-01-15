<?php

namespace App\Actions\Admin\Tools\State;

use App\Models\State;
use App\Services\Admin\Tools\StateService;
use App\Events\Admin\Tools\State\Create as Event;
use App\Traits\AuthUser;

class Create
{
    use AuthUser;

    protected $stateService;

    public function __construct(StateService $stateService)
    {
        $this->stateService = $stateService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): State
    {
        $state = $this->stateService->createState($data);
        event(new Event($state, $this->user));
        return $state;
    }
}
