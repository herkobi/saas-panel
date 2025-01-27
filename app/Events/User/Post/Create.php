<?php

namespace App\Events\User\Post;

use App\Models\Post;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $post;
    public $createdBy;

    public function __construct(Post $post, Authenticatable $createdBy)
    {
        $this->post = $post;
        $this->createdBy = $createdBy;
    }
}
