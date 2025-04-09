<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Content;
use App\Traits\AuthUser;
use App\Traits\TenantFileUpload;
use Illuminate\Support\Facades\Storage;

class ContentObserver
{
    use AuthUser, TenantFileUpload;

    public function __construct()
    {
        $this->initializeAuthUser();
    }

    /**
     * Handle the Content "creating" event.
     */
    public function creating(Content $content): void
    {
        // Tenant ID'yi otomatik olarak ayarla (HasTenant trait'i de benzer şeyi yapıyor)
        if ($this->user && $this->user->tenant_id) {
            $content->tenant_id = $this->user->tenant_id;
        }
    }

    /**
     * Handle the Content "created" event.
     */
    public function created(Content $content): void
    {
        // Activity log kaydı oluştur
        if ($this->user) {
            Activity::create([
                'tenant_id' => $content->tenant_id,
                'user_id' => $this->user->id,
                'message' => 'content.created',
                'log' => [
                    'action' => 'content_created',
                    'model' => 'Content',
                    'model_id' => $content->id,
                    'attributes' => [
                        'title' => $content->title,
                        'status' => $content->status->value,
                    ],
                    'user' => [
                        'id' => $this->user->id,
                        'name' => $this->user->name,
                    ],
                ],
            ]);
        }
    }

    /**
     * Handle the Content "saving" event.
     */
    public function saving(Content $content): void
    {
        // Dosya yükleme işlemini burada yap
        if (request()->hasFile('image')) {
            $file = request()->file('image');

            // Dosya adı olarak kampanya başlığını kullan ve Sluggable trait ile SEO dostu hale getir
            $extension = $file->getClientOriginalExtension();
            $filename = $content->generateSlug($content->title) . '.' . $extension;

            // TenantFileUpload trait'ini kullanarak belirtilen dosya adıyla yükle
            $uploadedFilename = $content->uploadTenantFile($file, null, $filename);

            if ($uploadedFilename) {
                $content->image = $uploadedFilename;
            }
        }
    }

    /**
     * Handle the Content "updated" event.
     */
    public function updated(Content $content): void
    {
        // Activity log kaydı oluştur
        if ($this->user) {
            $changedFields = $content->getChanges();

            // Sadece image değişimi için log tutmayalım (dosya yükleme işlemi)
            if (count($changedFields) === 1 && isset($changedFields['image'])) {
                return;
            }

            // Değişen alanları log'a ekle
            $logData = [
                'action' => 'content_updated',
                'model' => 'Content',
                'model_id' => $content->id,
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ],
                'changes' => [],
            ];

            foreach ($changedFields as $field => $newValue) {
                if ($field !== 'updated_at') {
                    $logData['changes'][$field] = [
                        'old' => $content->getOriginal($field),
                        'new' => $newValue,
                    ];
                }
            }

            Activity::create([
                'tenant_id' => $content->tenant_id,
                'user_id' => $this->user->id,
                'message' => 'content.updated',
                'log' => $logData,
            ]);
        }
    }

    /**
     * Handle the Content "deleted" event.
     */
    public function deleted(Content $content): void
    {
        // Dosya silme işlemini burada yap
        if ($content->image) {
            $content->deleteTenantFile($content->image);
        }

        // Activity log kaydı oluştur
        if ($this->user) {
            Activity::create([
                'tenant_id' => $content->tenant_id,
                'user_id' => $this->user->id,
                'message' => 'content.deleted',
                'log' => [
                    'action' => 'content_deleted',
                    'model' => 'Content',
                    'model_id' => $content->id,
                    'attributes' => [
                        'title' => $content->title,
                    ],
                    'user' => [
                        'id' => $this->user->id,
                        'name' => $this->user->name,
                    ],
                ],
            ]);
        }
    }
}
