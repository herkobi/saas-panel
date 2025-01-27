<?php

namespace App\Actions\User\Post;

use App\Models\Post;
use App\Traits\AuthUser;
use App\Services\User\Post\PostService;
use App\Events\User\Post\Delete as Event;

class Delete
{
    use AuthUser;

    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        $this->initializeAuthUser();
    }

    public function execute(string $id): Post
    {
        $post = $this->postService->getPostById($id);
        $this->postService->deletePost($id);
        event(new Event($post, $this->user));
        return $post;
    }
}
