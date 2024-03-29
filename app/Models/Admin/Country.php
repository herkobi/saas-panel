<?php

namespace App\Models\Admin;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'country',
        'slug',
        'code'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => Status::class
    ];

    /**
     * States.
    */
    public function states() {
        return $this->hasMany(State::class, 'country_id');
    }

    /**
     * Taxes.
    */
    public function taxes() {
        return $this->hasMany(Tax::class, 'country_id');
    }

}
