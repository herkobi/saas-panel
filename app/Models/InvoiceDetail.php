<?php

namespace App\Models;

use App\Traits\HasDefaultPagination;
use App\Traits\Owner;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceDetail extends Model
{
    use HasFactory, HasUuids, HasDefaultPagination, Owner;

    protected $table = "invoice_details";

    protected $fillable = [
        'user_id',
        'title',
        'invoiceName',
        'taxOffice',
        'taxNumber',
        'address',
        'zipCode',
        'state',
        'city',
        'country',
        'mersis',
        'phone',
        'email',
        'kep',
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
}
