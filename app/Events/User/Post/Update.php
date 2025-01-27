<?php

namespace App\Events\User\Post;

use App\Models\Post;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $post;
    public $changedBy;
    public $oldPost;
    public $newPost;

    public function __construct(Post $post, Authenticatable $changedBy, string $oldPost, string $newPost)
    {
        $this->post = $post;
        $this->changedBy = $changedBy;
        $this->oldPost = $oldPost;
        $this->newPost = $newPost;
    }
}
