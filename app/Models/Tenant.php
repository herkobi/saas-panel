<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'status',
        'settings',
    ];

    protected $casts = [
        'settings' => 'json',
        'status' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($tenant) {
            // Tenant oluşturulmadan önce public_path değerini settings içine ekle
            $settings = $tenant->settings ?: [];
            $settings['public_path'] = 'folder_' . Str::random(8);
            $tenant->settings = $settings;
        });

        static::created(function ($tenant) {
            // Tenant oluşturulduğunda otomatik olarak public klasörünü oluştur
            $tenant->createPublicDirectory();
        });

        static::deleted(function ($tenant) {
            // Tenant silindiğinde klasörü de sil
            $tenant->deletePublicDirectory();
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function owner()
    {
        return $this->users()->whereType('tenant_owner')->first();
    }

    /**
     * Tenant'a ait public klasör yolunu döndürür
     *
     * @return string
     */
    public function getPublicPath(): string
    {
        if (isset($this->settings['public_path'])) {
            return $this->settings['public_path'];
        }

        // Eğer kayıtlı bir public_path yoksa, oluştur ve kaydet
        $folderName = 'folder_' . Str::random(8);
        $settings = $this->settings ?: [];
        $settings['public_path'] = $folderName;
        $this->settings = $settings;
        $this->save();

        return $folderName;
    }

    /**
     * Tenant'a ait public klasörünü oluşturur
     *
     * @return bool
     */
    public function createPublicDirectory(): bool
    {
        return Storage::disk('public')->makeDirectory($this->getPublicPath());
    }

    /**
     * Tenant'a ait public klasörünü siler
     *
     * @return bool
     */
    public function deletePublicDirectory(): bool
    {
        return Storage::disk('public')->deleteDirectory($this->getPublicPath());
    }

    /**
     * Tenant'a ait abonelikler
     *
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Tenant'ın aktif aboneliğini getirir
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->latest()
            ->first();
    }

    /**
     * Tenant'ın aktif bir aboneliği olup olmadığını kontrol eder
     */
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription() !== null;
    }
}
