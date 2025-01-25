<?php

namespace App\Models;

use App\Enums\AccountStatus;
use App\Enums\UserType;
use App\Traits\HasDefaultPagination;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasUuids, HasDefaultPagination, TwoFactorAuthenticatable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'tenant_id',
        'is_tenant_owner',
        'status',
        'name',
        'surname',
        'email',
        'email_verified_at',
        'password',
        'last_login_at',
        'last_login_ip',
        'agent',
        'created_by',
        'created_by_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'last_login_at' => 'datetime',
            'status' => AccountStatus::class,
            'type' => UserType::class,
            'agent' => 'array',
            'is_tenant_owner' => 'boolean',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isTenantOwner(): bool
    {
        return $this->is_tenant_owner;
    }

    public function meta(): HasOne
    {
        return $this->hasOne(UserMeta::class);
    }

    public function authlogs(): HasMany
    {
        return $this->hasMany(Authlog::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'user_id');
    }

    public function agreements(): BelongsToMany
    {
        return $this->belongsToMany(Agreement::class, 'agreement_user')
            ->using(AgreementUser::class)
            ->withPivot(['agreement_version_id', 'accepted_at', 'ip_address', 'user_agent'])
            ->withTimestamps();
    }

    public function hasAcceptedVersion(AgreementVersion $version): bool
    {
        return $this->agreements()
            ->wherePivot('agreement_version_id', $version->id)
            ->exists();
    }
}
