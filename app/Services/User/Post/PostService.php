<?php

namespace App\Services\User\Post;

use App\Repositories\PostRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Post;

class PostService
{
    protected $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllPosts(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function getPostById(string $id, bool $withoutGlobalScope = false): Post
    {
        return $this->repository->getById($id, $withoutGlobalScope);
    }

    public function createPost(array $data): Post
    {
        return $this->repository->create($data);
    }

    public function updatePost(string $id, array $data): Post
    {
        return $this->repository->update($id, $data);
    }

    public function deletePost(string $id): void
    {
        $this->repository->delete($id);
    }
}
