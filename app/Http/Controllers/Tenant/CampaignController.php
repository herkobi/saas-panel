<?php

namespace App\Http\Controllers\Tenant;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Campaign;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\Tenant\CampaignService;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Tenant\Campaign\CampaignCreateRequest;
use App\Http\Requests\Tenant\Campaign\CampaignUpdateRequest;
use Illuminate\Http\Request;

class CampaignController extends Controller
{

    public function __construct(protected CampaignService $campaignService)
    {
    }

    /**
     * Display a listing of campaigns.
     */
    public function index(Request $request): Response
    {
        // Kampanyaları görüntüleme yetkisi kontrolü
        $this->authorize('viewAny', Campaign::class);

        // Sayfa ve limit değerlerini alıyoruz, varsayılan olarak sayfa 1, limit 12
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 12);

        $campaigns = $this->campaignService->getPaginatedCampaigns($page, $limit);
        $totalCount = $this->campaignService->getTotalCampaignCount();

        // Burada resimleri işleme kısmı aynı
        $mappedCampaigns = $campaigns->map(function ($campaign) {
            $imageUrl = null;
            if ($campaign->image && $campaign->tenant) {
                $imageUrl = asset('storage/' . $campaign->tenant->getPublicPath() . '/' . $campaign->image);
            }

            return [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'status' => $campaign->status,
                'start_date' => $campaign->start_date,
                'end_date' => $campaign->end_date,
                'link_count' => $campaign->link_count ?? 0,
                'image' => $imageUrl,
                'created_at' => $campaign->created_at->format('d.m.Y'),
            ];
        });

        return Inertia::render('tenant/campaigns/Index', [
            'campaigns' => [
                'data' => $mappedCampaigns,
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $totalCount,
                    'has_more' => ($page * $limit) < $totalCount
                ]
            ]
        ]);
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create(): Response
    {
        // Kampanya oluşturma yetkisi kontrolü
        $this->authorize('create', Campaign::class);

        return Inertia::render('tenant/campaigns/Create');
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(CampaignCreateRequest $request): RedirectResponse
    {
        // Kampanya oluşturma yetkisi kontrolü
        $this->authorize('create', Campaign::class);

        $campaign = $this->campaignService->createCampaign($request->validated());

        return $campaign
            ? Redirect::route('app.campaigns')->with('success', 'Kampanya başarıyla oluşturuldu.')
            : Redirect::back()->with('error', 'Kampanya oluşturulurken bir hata oluştu, lütfen tekrar deneyiniz.');
    }

    /**
     * Display the specified campaign.
     */
    public function show(Campaign $campaign): Response
    {
        // Kampanya görüntüleme yetkisi kontrolü
        $this->authorize('view', $campaign);

        return Inertia::render('tenant/campaigns/Show', [
            'campaign' => $campaign
        ]);
    }

    /**
     * Show the form for editing the specified campaign.
     */
    public function edit(Campaign $campaign): Response
    {
        // Kampanya düzenleme yetkisi kontrolü
        $this->authorize('update', $campaign);

        return Inertia::render('tenant/campaigns/Edit', [
            'campaign' => $campaign
        ]);
    }

    /**
     * Update the specified campaign in storage.
     */
    public function update(CampaignUpdateRequest $request, Campaign $campaign): RedirectResponse
    {
        // Kampanya güncelleme yetkisi kontrolü
        $this->authorize('update', $campaign);

        $updated = $this->campaignService->updateCampaign($campaign->id, $request->validated());

        return $updated
            ? Redirect::route('app.campaigns')->with('success', 'Kampanya başarıyla güncellendi.')
            : Redirect::back()->with('error', 'Kampanya güncellenirken bir hata oluştu, lütfen tekrar deneyiniz.');
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy(Campaign $campaign): RedirectResponse
    {
        // Kampanya silme yetkisi kontrolü
        $this->authorize('delete', $campaign);

        $deleted = $this->campaignService->deleteCampaign($campaign->id);

        return $deleted
            ? Redirect::route('app.campaigns')->with('success', 'Kampanya başarıyla silindi.')
            : Redirect::route('app.campaigns')->with('error', 'Kampanya silinirken bir hata oluştu, lütfen tekrar deneyiniz.');
    }
}
