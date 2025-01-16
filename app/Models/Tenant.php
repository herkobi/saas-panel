<?php

namespace App\Models;

use App\Enums\AccountStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LucasDotVin\Soulbscription\Models\Concerns\HasSubscriptions;

class Tenant extends Model
{
    use HasUuids, HasSubscriptions;

    protected $fillable = [
        'code',
        'domain',
        'has_domain',
        'status',
        'settings',
        'storage_folder',
    ];

    protected function casts(): array
    {
        return [
            'has_domain' => 'boolean',
            'status' => AccountStatus::class,
            'settings' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];
    }

    public static function generateCode(): string
    {
        $code = 'TNT' . strtoupper(Str::random(6));

        // Uniquelik kontrolü
        if (static::where('code', $code)->exists()) {
            return static::generateCode(); // Recursive olarak yeni kod üret
        }

        return $code;
    }

    // Tenant.php
    protected static function booted()
    {
        static::creating(function ($tenant) {
            // Benzersiz folder adı oluştur
            $tenant->storage_folder = config('tenant.storage.prefix') . Str::random(7);
        });

        static::created(function ($tenant) {
            // Tenant klasörlerini private altında oluştur
            $paths = [
                'private/tenants/' . $tenant->storage_folder,
                'private/tenants/' . $tenant->storage_folder . '/shared',
                'private/tenants/' . $tenant->storage_folder . '/users'
            ];

            foreach ($paths as $path) {
                File::ensureDirectoryExists(storage_path('app/' . $path));
            }
        });

        static::deleted(function ($tenant) {
            // Tenant silindiğinde klasörünü sil
            Storage::deleteDirectory('private/tenants/' . $tenant->storage_folder);
        });
    }

    // Helper metodlar güncellendi
    public function getSharedPath(): string
    {
        return 'private/tenants/' . $this->storage_folder . '/shared';
    }

    public function getUserPath(string $user_folder): string
    {
        return 'private/tenants/' . $this->storage_folder . '/users/' . $user_folder;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
