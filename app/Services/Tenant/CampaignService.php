<?php

namespace App\Services\Tenant;

use App\Models\Campaign;
use App\Services\Tenant\LinkService;
use Illuminate\Pagination\LengthAwarePaginator;

class CampaignService
{
    protected $model = Campaign::class;

    public function __construct(protected LinkService $linkService)
    {
    }

    /**
     * Get all campaigns with pagination and link count
     */
    public function getAllCampaigns(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model::withCount('link')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getPaginatedCampaigns(int $page = 1, int $limit = 12)
    {
        return Campaign::with('tenant')
            ->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();
    }

    public function getTotalCampaignCount(): int
    {
        return Campaign::count();
    }

    /**
     * Get campaign by ID
     */
    public function getCampaignById(string $id): ?Campaign
    {
        return $this->model::find($id);
    }

    /**
     * Create a new campaign
     */
    public function createCampaign(array $data): ?Campaign
    {
        if (!empty($data['external_link'])) {
            $linkData = ['url' => $data['external_link']];
            $link = $this->linkService->createLink($linkData);
            $data['link_id'] = $link->id;
        }

        return $this->model::create($data);
    }

    /**
     * Update a campaign
     */
    public function updateCampaign(string $id, array $data): bool
    {
        $campaign = $this->getCampaignById($id);

        if (!$campaign) {
            return false;
        }

        // External link değişmişse yeni link oluştur
        if (!empty($data['external_link']) && $data['external_link'] !== $campaign->external_link) {
            $linkData = ['url' => $data['external_link']];
            $link = $this->linkService->createLink($linkData);
            $data['link_id'] = $link->id;
        }

        return $campaign->update($data);
    }

    /**
     * Delete a campaign
     */
    public function deleteCampaign(string $id): bool
    {
        $campaign = $this->getCampaignById($id);

        if (!$campaign) {
            return false;
        }

        return $campaign->delete();
    }
}
