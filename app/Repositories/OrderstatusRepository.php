<?php

namespace App\Repositories;

use App\Enums\Status;
use App\Models\Orderstatus;
use Illuminate\Database\Eloquent\Collection;

class OrderstatusRepository extends BaseRepository
{
    protected $model = Orderstatus::class;

    public function getActiveOrderstatuses(): Collection
    {
        return Orderstatus::where('status', Status::ACTIVE)
            ->orderBy('title', 'asc')
            ->get();
    }

    public function getOrderstatusByCode(string $code): Orderstatus
    {
        return Orderstatus::where('code', $code)->first();
    }
}
