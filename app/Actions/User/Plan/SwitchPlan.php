<?php

namespace App\Actions\User\Plan;

use App\Models\Plan;
use App\Traits\AuthUser;
use App\Events\User\Plan\Create as Event;
use App\Services\User\PlanService;

class SwitchPlan
{
    use AuthUser;

    protected $postService;

    public function __construct(PlanService $postService)
    {
        $this->postService = $postService;
        $this->initializeAuthUser();
    }

    public function execute(int $id): Plan
    {
        $post = $this->postService->switchPlan($id);
        event(new Event($post, $this->user));
        return $post;
    }
}
