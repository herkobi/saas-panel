<?php

namespace App\Models;

use App\Enums\AgreementVersionStatus;
use App\Enums\Status;
use App\Enums\UserType;
use App\Traits\HasDefaultPagination;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class Agreement extends Model
{
    use HasFactory, HasUuids, Sluggable, HasDefaultPagination, SoftDeletes;

    protected $table = "agreements";
    protected $fillable = [
        'status',
        'user_type',
        'title',
        'slug',
        'description',
        'show_on_register',
        'show_on_payment'
    ];

    protected function casts(): array
    {
        return [
            'show_on_register' => 'boolean',
            'show_on_payment' => 'boolean',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'status' => Status::class,
            'user_type' => UserType::class,
        ];
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = $this->generateSlug($value);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(AgreementVersion::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'agreement_user')
                    ->using(AgreementUser::class)
                    ->withPivot(['agreement_version_id', 'accepted_at', 'ip_address', 'user_agent'])
                    ->withTimestamps();
    }

    public function latestVersion()
    {
        return $this->versions()->where('status', AgreementVersionStatus::PUBLISHED)
                    ->latest('published_at')
                    ->first();
    }

    public static function getUserTypeOptions(): array
    {
        return collect(UserType::cases())
            ->filter(function ($userType) {
                return !in_array($userType->value, ['DEMO', 'SUPER']);
            })
            ->mapWithKeys(function ($userType) {
                return [$userType->value => $userType->title()];
            })
            ->toArray();
    }

    public function isApplicableToUserType(UserType $userType): bool
    {
        return in_array($userType->value, $this->user_types ?? ['USER']);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($agreement) {
            // Aktif versiyonları kontrol et
            if ($agreement->versions()->whereNull('deleted_at')->exists()) {
                throw ValidationException::withMessages([
                    'versions' => 'Bu sözleşme silinemez. Önce tüm versiyonlarını silmelisiniz.'
                ]);
            }
        });
    }
}
