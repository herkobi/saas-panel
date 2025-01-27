<?php

namespace App\Listeners\User\Post;

use App\Events\User\Post\Create as Event;
use App\Models\Activity;
use App\Services\LoggingService;
use App\Traits\LogActivity;

class Create
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
            'post.created',
            $event->createdBy,
            $event->post,
            [
                'post_title' => $event->post->title,
            ]
        );

        Activity::create([
            'message' => 'post.created',
            'log' => $this->logActivity(
                ' user created new post',
                $event->createdBy,
                $event->post,
                [
                    'post_title' => $event->post->title,
                ]
            ),
        ]);
    }
}
