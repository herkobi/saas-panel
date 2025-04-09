<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Campaign;
use App\Traits\AuthUser;
use App\Traits\TenantFileUpload;
use Illuminate\Support\Facades\Storage;

class CampaignObserver
{
    use AuthUser, TenantFileUpload;

    public function __construct()
    {
        $this->initializeAuthUser();
    }

    /**
     * Handle the Campaign "creating" event.
     */
    public function creating(Campaign $campaign): void
    {
        // Tenant ID'yi otomatik olarak ayarla (HasTenant trait'i de benzer şeyi yapıyor)
        if ($this->user && $this->user->tenant_id) {
            $campaign->tenant_id = $this->user->tenant_id;
        }
    }

    /**
     * Handle the Campaign "created" event.
     */
    public function created(Campaign $campaign): void
    {
        // Activity log kaydı oluştur
        if ($this->user) {
            Activity::create([
                'tenant_id' => $campaign->tenant_id,
                'user_id' => $this->user->id,
                'message' => 'campaign.created',
                'log' => [
                    'action' => 'campaign_created',
                    'model' => 'Campaign',
                    'model_id' => $campaign->id,
                    'attributes' => [
                        'title' => $campaign->title,
                        'status' => $campaign->status->value,
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
     * Handle the Campaign "saving" event.
     */
    public function saving(Campaign $campaign): void
    {
        // Dosya yükleme işlemini burada yap
        if (request()->hasFile('image')) {
            $file = request()->file('image');

            // Dosya adı olarak kampanya başlığını kullan ve Sluggable trait ile SEO dostu hale getir
            $extension = $file->getClientOriginalExtension();
            $filename = $campaign->generateSlug($campaign->title) . '.' . $extension;

            // TenantFileUpload trait'ini kullanarak belirtilen dosya adıyla yükle
            $uploadedFilename = $campaign->uploadTenantFile($file, null, $filename);

            if ($uploadedFilename) {
                $campaign->image = $uploadedFilename;
            }
        }
    }

    /**
     * Handle the Campaign "updated" event.
     */
    public function updated(Campaign $campaign): void
    {
        // Activity log kaydı oluştur
        if ($this->user) {
            $changedFields = $campaign->getChanges();

            // Sadece image değişimi için log tutmayalım (dosya yükleme işlemi)
            if (count($changedFields) === 1 && isset($changedFields['image'])) {
                return;
            }

            // Değişen alanları log'a ekle
            $logData = [
                'action' => 'campaign_updated',
                'model' => 'Campaign',
                'model_id' => $campaign->id,
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ],
                'changes' => [],
            ];

            foreach ($changedFields as $field => $newValue) {
                if ($field !== 'updated_at') {
                    $logData['changes'][$field] = [
                        'old' => $campaign->getOriginal($field),
                        'new' => $newValue,
                    ];
                }
            }

            Activity::create([
                'tenant_id' => $campaign->tenant_id,
                'user_id' => $this->user->id,
                'message' => 'campaign.updated',
                'log' => $logData,
            ]);
        }
    }

    /**
     * Handle the Campaign "deleted" event.
     */
    public function deleted(Campaign $campaign): void
    {
        // Dosya silme işlemini burada yap
        if ($campaign->image) {
            $campaign->deleteTenantFile($campaign->image);
        }

        // Activity log kaydı oluştur
        if ($this->user) {
            Activity::create([
                'tenant_id' => $campaign->tenant_id,
                'user_id' => $this->user->id,
                'message' => 'campaign.deleted',
                'log' => [
                    'action' => 'campaign_deleted',
                    'model' => 'Campaign',
                    'model_id' => $campaign->id,
                    'attributes' => [
                        'title' => $campaign->title,
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
