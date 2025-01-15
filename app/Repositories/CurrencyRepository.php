<?php

namespace App\Repositories;

use App\Enums\Status;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CurrencyRepository extends BaseRepository
{
    protected $model = Currency::class;

    public function getAllCurrencies(): LengthAwarePaginator
    {
        return Currency::orderBy('status', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(30);
    }

    public function getActiveCurrencies(): Collection
    {
        return Currency::where('status', Status::ACTIVE)
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getByCode(string $code): Currency
    {
        return Currency::where('iso_code', $code)->firstOrFail();
    }
}
