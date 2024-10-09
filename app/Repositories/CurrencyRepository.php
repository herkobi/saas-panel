<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository extends BaseRepository
{
    protected $model = Currency::class;

    public function getBySlug(string $slug)
    {
        return $this->model::where('slug', $slug)->firstOrFail();
    }
}
