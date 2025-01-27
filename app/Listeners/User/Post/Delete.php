<?php

namespace App\Listeners\User\Post;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\User\Post\Delete as Event;
use App\Traits\LogActivity;

class Delete
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
            'post.deleted',
            $event->deletedBy,
            $event->post,
            [
                'post_title' => $event->post->title,
            ]
        );

        Activity::create([
            'message' => 'post.deleted',
            'log' => $this->logActivity(
                ' user deleted post ',
                $event->deletedBy,
                $event->post,
                [
                    'post_title' => $event->post->title
                ]
            ),
        ]);
    }
}
