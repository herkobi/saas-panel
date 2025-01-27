<?php

namespace App\Events\User\Post;

use App\Models\Post;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Delete
{
    use Dispatchable, SerializesModels;

    public $post;
    public $deletedBy;

    public function __construct(Post $post, Authenticatable $deletedBy)
    {
        $this->post = $post;
        $this->deletedBy = $deletedBy;
    }
}
