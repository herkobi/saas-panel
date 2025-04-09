<?php

namespace App\Services\Tenant;

use App\Models\Space;
use Illuminate\Pagination\LengthAwarePaginator;

class SpaceService
{
    protected $model = Space::class;

    /**
     * Get all spaces with pagination and link count
     */
    public function getAllSpaces(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model::withCount('links')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get space by ID
     */
    public function getSpaceById(int $id): ?Space
    {
        return $this->model::find($id);
    }

    /**
     * Create a new space
     */
    public function createSpace(array $data): ?Space
    {
        return $this->model::create([
            'name' => $data['name'],
            'color' => $data['color'],
        ]);
    }

    /**
     * Update a space
     */
    public function updateSpace(int $id, array $data): bool
    {
        $space = $this->getSpaceById($id);

        if (!$space) {
            return false;
        }

        return $space->update([
            'name' => $data['name'],
            'color' => $data['color'],
        ]);
    }

    /**
     * Delete a space
     */
    public function deleteSpace(int $id): bool
    {
        $space = $this->getSpaceById($id);

        if (!$space) {
            return false;
        }

        return $space->delete();
    }
}
