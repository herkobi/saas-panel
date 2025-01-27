<?php

namespace App\Actions\User\Post;

use App\Models\Post;
use App\Traits\AuthUser;
use App\Services\User\Post\PostService;
use App\Events\User\Post\Update as Event;

class Update
{
    use AuthUser;

    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): Post
    {
        $oldPost = $this->postService->getPostById($id);
        $post = $this->postService->updatePost($id, $data);
        $newPost = $this->postService->getPostById($id);
        event(new Event($post, $this->user, $oldPost, $newPost));
        return $post;
    }
}
