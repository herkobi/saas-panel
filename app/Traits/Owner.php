<?php

namespace App\Traits;

use App\Scopes\GlobalQuery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Owner
{
    protected static function bootOwner(): void
    {
        if (app()->runningInConsole() || !Auth::check()) {
            return;
        }

        static::creating(function (Model $model) {
            // Eğer user_id zaten set edilmişse, onu koruyalım
            if (!$model->isDirty('user_id')) {
                $model->user_id = Auth::id();
            }
        });

        static::updating(function (Model $model) {
            if (!$model->isDirty('user_id')) {
                $model->user_id = Auth::id();
            }
        });

        static::addGlobalScope(new GlobalQuery);
    }
}
