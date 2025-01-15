<?php

namespace App\Models;

use App\Enums\AgreementVersionStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgreementVersion extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = "agreement_versions";

    protected $fillable = [
        'agreement_id',
        'status',
        'version',
        'content',
        'published_at',
        'require_acceptance',
        'block_access',
        'send_notification'
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'require_acceptance' => 'boolean',
            'block_access' => 'boolean',
            'send_notification' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'status' => AgreementVersionStatus::class,
        ];
    }

    public function agreement(): BelongsTo
    {
        return $this->belongsTo(Agreement::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'agreement_user')
                    ->withPivot(['accepted_at', 'ip_address', 'user_agent'])
                    ->withTimestamps();
    }

    public function canBeEdited(): bool
    {
        return $this->status === AgreementVersionStatus::DRAFT;
    }

}
