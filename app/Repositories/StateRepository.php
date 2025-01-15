<?php

namespace App\Repositories;

use App\Enums\Status;
use App\Models\State;
use Illuminate\Database\Eloquent\Collection;

class StateRepository extends BaseRepository
{
    protected $model = State::class;

    public function getAllStates($country): Collection
    {
        return State::where('country_id', $country)->orderBy('name', 'asc')->get();
    }

    public function getActiveStates(): Collection
    {
        return State::where('status', Status::ACTIVE)->get();
    }

    public function getCountryStates(string $id): Collection
    {
        return State::where('country_id', $id)
            ->where('status', Status::ACTIVE)
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
    }
}
