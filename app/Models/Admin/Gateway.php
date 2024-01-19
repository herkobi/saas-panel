<?php

namespace App\Models\Admin;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory;

    protected $table = 'gateways';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'title',
        'desc',
        'payment_id',
        'currency_id',
        'value'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => Status::class,
        'value' => 'array'
    ];

    /**
     * Selected Payment
     */
    public function payment() {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    /**
     * Selected Currency
     */
    public function currency() {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
