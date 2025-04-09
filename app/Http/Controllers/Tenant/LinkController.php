<?php

namespace App\Http\Controllers\Tenant;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Pixel;
use App\Models\Space;
use App\Models\Link;
use Inertia\Response;
use App\Services\Tenant\StatService;
use App\Services\Tenant\LinkService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Tenant\Link\LinkCreateRequest;
use App\Http\Requests\Tenant\Link\LinkUpdateUtmInfoRequest;
use App\Http\Requests\Tenant\Link\LinkUpdateBasicInfoRequest;
use App\Http\Requests\Tenant\Link\LinkUpdateExtraInfoRequest;
use App\Http\Requests\Tenant\Link\LinkUpdateTargetInfoRequest;

class LinkController extends Controller
{
    /**
     * Display a listing of the links.
     */
    public function __construct(protected LinkService $linkService, protected StatService $statService)
    {
    }

    /**
     * Display a listing of the links.
     */
    public function index(): Response
    {
        $this->authorize('viewAny', Link::class);

        $links = $this->linkService->getAllLinks(10);

        return Inertia::render('tenant/links/Index', [
            'links' => $links->map(function ($link) {
                return [
                    'id' => $link->id,
                    'url' => $link->url,
                    'alias' => $link->alias,
                    'title' => $link->title,
                    'disabled' => (bool) $link->disabled,
                    'clicks' => $link->clicks,
                    'space' => $link->space ? [
                        'id' => $link->space->id,
                        'name' => $link->space->name,
                        'color' => $link->space->color,
                    ] : null,
                    'campaign' => $link->campaign ? [
                        'id' => $link->campaign->id,
                        'title' => $link->campaign->title,
                    ] : null,
                    'expiration_clicks' => $link->expiration_clicks,
                    'ends_at' => $link->ends_at,
                    'is_expired' => $link->ends_at && Carbon::now()->greaterThan($link->ends_at),
                    'click_limit_reached' => $link->expiration_clicks && $link->clicks >= $link->expiration_clicks,
                    'created_at' => $link->created_at->diffForHumans(),
                    'created_at_formatted' => $link->created_at->format('Y-m-d'),
                ];
            }),
            'pagination' => [
                'current_page' => $links->currentPage(),
                'last_page' => $links->lastPage(),
                'per_page' => $links->perPage(),
                'total' => $links->total(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new link.
     */
    public function create(): Response
    {
        $this->authorize('create', Link::class);

        $spaces = Space::all()->map(function ($space) {
            return [
                'id' => $space->id,
                'name' => $space->name,
                'color' => $space->color,
            ];
        });

        $pixels = Pixel::all()->map(function ($pixel) {
            return [
                'id' => $pixel->id,
                'name' => $pixel->name,
                'type' => $pixel->type,
            ];
        });

        return Inertia::render('tenant/links/Create', [
            'spaces' => $spaces,
            'pixels' => $pixels,
            'countries' => config('data.countries'),
            'platforms' => config('data.platforms'),
            'languages' => config('data.languages'),
        ]);
    }

    /**
     * Store a newly created link in storage.
     */
    public function store(LinkCreateRequest $request): RedirectResponse
    {
        $this->authorize('create', Link::class);

        $data = $request->validated();

        // Çoklu URL modu kontrolü
        if (isset($data['is_multiple']) && $data['is_multiple'] && isset($data['multiple_urls'])) {
            // Çoklu URL'leri işle
            $links = $this->linkService->createMultipleLinks($data['multiple_urls']);
            return redirect()->route('app.links')->with('success', 'Linkler başarıyla oluşturuldu.');
        } else {
            // Tekli URL oluştur
            $link = $this->linkService->createLink($data);
            return redirect()->route('app.links')->with('success', 'Link başarıyla oluşturuldu.');
        }
    }

    public function show(int $id): Response
    {
        $link = $this->linkService->getLinkById($id);

        if (!$link) {
            abort(404);
        }

        $this->authorize('view', $link);

        // StatService'den son 7 günlük istatistikleri al
        $stats = $this->statService->getLinkSummaryStats($link);

        // Form için gerekli verileri hazırla
        $spaces = Space::all();
        $pixels = Pixel::all();

        return Inertia::render('tenant/links/Detail', [
            'link' => [
                'id' => $link->id,
                'url' => $link->url,
                'alias' => $link->alias,
                'title' => $link->title,
                'description' => $link->description,
                'image' => $link->image,
                'disabled' => (bool) $link->disabled,
                'clicks' => $link->clicks,
                'expiration_clicks' => $link->expiration_clicks,
                'expiration_url' => $link->expiration_url,
                'ends_at' => $link->ends_at ? $link->ends_at->toISOString() : null,
                'published_at' => $link->published_at ? $link->published_at->toISOString() : null,
                'password' => $link->password,
                'password_hint' => $link->password_hint,
                'space' => $link->space ? [
                    'id' => $link->space->id,
                    'name' => $link->space->name,
                    'color' => $link->space->color,
                ] : null,
                'target_type' => $link->target_type,
                'goal' => $link->goal,
                'country_target' => $link->country_target,
                'platform_target' => $link->platform_target,
                'language_target' => $link->language_target,
                'rotation_target' => $link->rotation_target,
                'utm_source' => $link->utm_source,
                'utm_medium' => $link->utm_medium,
                'utm_campaign' => $link->utm_campaign,
                'utm_term' => $link->utm_term,
                'utm_content' => $link->utm_content,
                'pixels' => $link->pixels->map(function ($pixel) {
                    return [
                        'id' => $pixel->id,
                        'name' => $pixel->name,
                        'type' => $pixel->type,
                    ];
                }),
                'created_at' => $link->created_at->toISOString(),
            ],
            'uniqueClicks' => $this->statService->getUniqueClicks($link->id),
            'conversionRate' => $link->goal ?
                round(($this->statService->getUniqueClicks($link->id) / $link->goal) * 100, 1) . '%' :
                'Hedef Yok',
            'stats' => $stats,
            'spaces' => $spaces->map(function ($space) {
                return [
                    'id' => $space->id,
                    'name' => $space->name,
                    'color' => $space->color,
                ];
            }),
            'pixels' => $pixels->map(function ($pixel) {
                return [
                    'id' => $pixel->id,
                    'name' => $pixel->name,
                    'type' => $pixel->type,
                ];
            }),
            'countries' => config('data.countries'),
            'platforms' => config('data.platforms'),
            'languages' => config('data.languages'),
        ]);
    }

    /**
     * Update link basic information
     */
    public function updateBasicInfo(int $id, LinkUpdateBasicInfoRequest $request): RedirectResponse
    {
        $link = $this->linkService->getLinkById($id);

        if (!$link) {
            abort(404);
        }

        $this->authorize('update', $link);

        $data = $request->validated();
        $link = $this->linkService->updateBasicInfo($id, $data);

        return back()->with('success', 'Link temel bilgileri başarıyla güncellendi.');
    }

    /**
     * Update link extra information
     */
    public function updateExtraInfo(int $id, LinkUpdateExtraInfoRequest $request): RedirectResponse
    {
        $link = $this->linkService->getLinkById($id);

        if (!$link) {
            abort(404);
        }

        $this->authorize('update', $link);

        $data = $request->validated();
        $link = $this->linkService->updateExtraInfo($id, $data);

        return back()->with('success', 'Link ek özellikleri başarıyla güncellendi.');
    }

    /**
     * Update link's UTM parameters
     */
    public function updateUtmInfo(int $id, LinkUpdateUtmInfoRequest $request): RedirectResponse
    {
        $link = $this->linkService->getLinkById($id);

        if (!$link) {
            abort(404);
        }

        $this->authorize('update', $link);

        $data = $request->validated();
        $link = $this->linkService->updateUtmInfo($id, $data);

        return back()->with('success', 'UTM parametreleri başarıyla güncellendi.');
    }

    /**
     * Update link's targeting information
     */
    public function updateTargetInfo(int $id, LinkUpdateTargetInfoRequest $request): RedirectResponse
    {
        $link = $this->linkService->getLinkById($id);

        if (!$link) {
            abort(404);
        }

        $this->authorize('update', $link);

        $data = $request->validated();
        $link = $this->linkService->updateTargetInfo($id, $data);

        return back()->with('success', 'Hedefleme bilgileri başarıyla güncellendi.');
    }

    /**
     * Toggle link status (enable/disable)
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $link = $this->linkService->getLinkById($id);
        if (!$link) {
            return back()->with('error', 'Link bulunamadı.');
        }

        $this->authorize('toggleStatus', $link);

        // Durumu tersine çevir (toggle)
        $this->linkService->toggleStatus($id);

        return back()->with('success', $link->disabled ? 'Link aktifleştirildi.' : 'Link donduruldu.');
    }

    /**
     * Delete a link
     */
    public function destroy(int $id): RedirectResponse
    {
        $link = $this->linkService->getLinkById($id);
        if (!$link) {
            return back()->with('error', 'Link bulunamadı.');
        }

        $this->authorize('delete', $link);

        // Linki sil
        $this->linkService->deleteLink($id);

        return redirect()->route('app.links')->with('success', 'Link başarıyla silindi.');
    }
}
