<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait HasDefaultPagination
{
    public function scopeDefaultPagination($query): LengthAwarePaginator
    {
        return $query->orderByRaw('COALESCE(updated_at, created_at) DESC')
                     ->paginate(30);
    }

    public function scopeOrderedPagination($query): LengthAwarePaginator
    {
        return $query->orderBy('order', 'asc')
                     ->orderBy('title', 'asc')
                     ->paginate(30);
    }

    public function scopeHierarchicalOrder($query): LengthAwarePaginator
    {
        return $query->with('parent')
                    ->selectRaw('*, IF(parent_id IS NULL, id, parent_id) as group_id')
                    ->orderBy('group_id')
                    ->orderBy('parent_id', 'asc')
                    ->orderBy('order', 'asc')
                    ->orderBy('title', 'asc')
                    ->paginate(30);
    }
}
