<?php

namespace App\Actions\User\Post;

use App\Models\Post;
use App\Traits\AuthUser;
use App\Services\User\Post\PostService;
use App\Events\User\Post\Create as Event;

class Create
{
    use AuthUser;

    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): Post
    {
        $post = $this->postService->createPost($data);
        event(new Event($post, $this->user));
        return $post;
    }
}
