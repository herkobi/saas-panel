<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class GlobalQuery implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if(auth()->user()){
            $builder->where('tenant_id', auth()->user()->tenant_id);
        }
    }
}
