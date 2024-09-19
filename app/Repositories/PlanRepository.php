<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RealRashid\PlanCraft\Facades\PlanCraft;
use RealRashid\PlanCraft\Models\Plan;

class PlanRepository extends BaseRepository
{
    protected $model = Plan::class;

    public function getAllPlans(): Collection
    {
        return PlanCraft::plans();
    }
}
