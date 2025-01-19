<?php

namespace App\Models;

use App\Traits\HasDefaultPagination;
use App\Traits\HasTenant;
use App\Traits\Owner;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, HasUuids, HasDefaultPagination, Owner, HasTenant, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'code',
        'user_id',
        'tenant_id',
        'plan_id',
        'orderstatus_id',
        'currency_id',
        'amount',
        'total_amount',
        'invoice_data',
        'payment_type',
        'document',
        'notes',
        'payment_date'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'payment_date' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'invoice_data' => 'array'
        ];
    }

    public static function generateCode(): string
    {
        $code = 'ORD' . strtoupper(Str::random(8));

        // Uniquelik kontrolü
        if (static::where('code', $code)->exists()) {
            return static::generateCode(); // Recursive olarak yeni kod üret
        }

        return $code;
    }

    protected static function booted(): void
    {
        static::creating(function ($order) {
            $order->code = $order->code ?? static::generateCode();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function orderstatus(): BelongsTo
    {
        return $this->belongsTo(Orderstatus::class, 'orderstatus_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function scopeWithStatus($query, string|array $statusCode, array $relations = ['orderstatus', 'user', 'tenant', 'plan'])
    {
        return $query->withoutGlobalScopes()
            ->with($relations)
            ->whereHas('orderstatus', function($query) use ($statusCode) {
                if (is_array($statusCode)) {
                    $query->whereIn('code', $statusCode);
                } else {
                    $query->where('code', $statusCode);
                }
            });
    }
}
