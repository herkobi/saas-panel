<?php

namespace App\Listeners\User\Post;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\User\Post\Update as Event;
use App\Traits\LogActivity;

class Update
{
    use LogActivity;

    protected $loggingService;
    protected $activity;

    public function __construct(LoggingService $loggingService, Activity $activity)
    {
        $this->loggingService = $loggingService;
        $this->activity = $activity;
    }

    public function handle(Event $event)
    {
        $this->loggingService->logUserAction(
            'post.updated',
            $event->changedBy,
            $event->post,
            [
                'new_post' => $event->newPost,
                'old_post' => $event->oldPost
            ]
        );

        Activity::create([
            'message' => 'post.updated',
            'log' => $this->logActivity(
                'user updated post of',
                $event->changedBy,
                $event->post,
                [
                    'old_post' => $event->oldPost,
                    'new_post' => $event->newPost
                ]
            ),
        ]);
    }
}
