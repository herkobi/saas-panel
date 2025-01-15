<?php

namespace App\Models;

use App\Traits\HasDefaultPagination;
use App\Traits\Owner;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAccount extends Model
{
    use HasFactory, HasUuids, HasDefaultPagination, Owner;

    protected $table = "accounts";

    protected $fillable = [
        'user_id',
        'invoice_name',
        'tax_office',
        'tax_number',
        'address',
        'zip_code',
        'district',
        'state_id',
        'country_id',
        'mersis',
        'phone',
        'gsm',
        'email',
        'kep'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
