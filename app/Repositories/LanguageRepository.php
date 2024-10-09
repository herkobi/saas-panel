<?php

namespace App\Repositories;

use App\Models\Language;

class LanguageRepository extends BaseRepository
{
    protected $model = Language::class;

    public function getBySlug(string $slug)
    {
        return $this->model::where('slug', $slug)->firstOrFail();
    }
}
