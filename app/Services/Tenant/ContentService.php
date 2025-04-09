<?php

namespace App\Services\Tenant;

use App\Models\Content;
use Illuminate\Pagination\LengthAwarePaginator;

class ContentService
{
    protected $model = Content::class;

    /**
     * Get all content with pagination and link count
     */
    public function getAllContents(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model::withCount('links')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get content by ID
     */
    public function getContentById(int $id): ?Content
    {
        return $this->model::find($id);
    }

    /**
     * Create a new content
     */
    public function createContent(array $data): ?Content
    {
        return $this->model::create([
            'name' => $data['name'],
            'color' => $data['color'],
        ]);
    }

    /**
     * Update a content
     */
    public function updateContent(int $id, array $data): bool
    {
        $content = $this->getContentById($id);

        if (!$content) {
            return false;
        }

        return $content->update([
            'name' => $data['name'],
            'color' => $data['color'],
        ]);
    }

    /**
     * Delete a content
     */
    public function deleteContent(int $id): bool
    {
        $content = $this->getContentById($id);

        if (!$content) {
            return false;
        }

        return $content->delete();
    }
}
