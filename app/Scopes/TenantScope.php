<?php

namespace App\Scopes;

use App\Traits\AuthUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    use AuthUser;

    public function __construct()
    {
        $this->initializeAuthUser();
    }

    public function apply(Builder $builder, Model $model)
    {
        if ($this->user) {
            $builder->where('tenant_id', $this->user->tenant_id);
        }
    }
}
