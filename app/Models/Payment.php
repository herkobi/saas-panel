<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, HasTenant;

    protected $fillable = [
        'tenant_id',
        'subscription_id',
        'payment_method',
        'transaction_id',
        'amount',
        'tax_amount',
        'total_amount',
        'currency_code',
        'status',
        'paid_at',
        'billing_name',
        'billing_address',
        'billing_city',
        'billing_district',
        'billing_postal_code',
        'billing_tax_office',
        'billing_tax_number',
        'billing_email',
        'billing_contact_name',
        'billing_phone',
        'payment_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'payment_data' => 'json',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
