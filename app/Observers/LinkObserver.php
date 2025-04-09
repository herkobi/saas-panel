<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Link;
use App\Services\Tenant\FeatureUsageService;
use App\Traits\AuthUser;

class LinkObserver
{
    use AuthUser;

    protected $featureName = 'link-yonetimi';

    public function __construct(protected FeatureUsageService $featureUsage)
    {
        $this->initializeAuthUser();
    }

    /**
     * Handle the Link "creating" event.
     */
    public function creating(Link $link): void
    {
        // Tenant ID'yi otomatik olarak ayarla (HasTenant trait'i de benzer şeyi yapıyor)
        if ($this->user && $this->user->tenant_id) {
            $link->tenant_id = $this->user->tenant_id;
        }
    }

    /**
     * Handle the Link "created" event.
     */
    public function created(Link $link): void
    {

        // Tenant'a ait ise özellik kullanımını artır
        if ($link->tenant_id) {
            $tenant = $link->tenant;
            $this->featureUsage->incrementUsage($tenant, $this->featureName);
        }

        // Activity log kaydı oluştur
        if ($this->user) {
            Activity::create([
                'tenant_id' => $link->tenant_id,
                'user_id' => $this->user->id,
                'message' => 'link.created',
                'log' => [
                    'action' => 'link_created',
                    'model' => 'Link',
                    'model_id' => $link->id,
                    'attributes' => [
                        'url' => $link->url,
                        'alias' => $link->alias,
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
     * Handle the Link "updated" event.
     */
    public function updated(Link $link): void
    {
        // Activity log kaydı oluştur
        if ($this->user) {
            $changedFields = $link->getChanges();

            // Değişen alanları log'a ekle
            $logData = [
                'action' => 'link_updated',
                'model' => 'Link',
                'model_id' => $link->id,
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ],
                'changes' => [],
            ];

            // Değişen alanları log'a ekle (updated_at hariç)
            foreach ($changedFields as $field => $newValue) {
                if ($field !== 'updated_at') {
                    $logData['changes'][$field] = [
                        'old' => $link->getOriginal($field),
                        'new' => $newValue,
                    ];
                }
            }

            // Eğer değişen alanlar varsa log oluştur
            if (!empty($logData['changes'])) {
                Activity::create([
                    'tenant_id' => $link->tenant_id,
                    'user_id' => $this->user->id,
                    'message' => 'link.updated',
                    'log' => $logData,
                ]);
            }
        }
    }

    /**
     * Handle the Link "deleted" event.
     */
    public function deleted(Link $link): void
    {
        // Activity log kaydı oluştur
        if ($this->user) {
            Activity::create([
                'tenant_id' => $link->tenant_id,
                'user_id' => $this->user->id,
                'message' => 'link.deleted',
                'log' => [
                    'action' => 'link_deleted',
                    'model' => 'Link',
                    'model_id' => $link->id,
                    'attributes' => [
                        'url' => $link->url,
                        'alias' => $link->alias,
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
