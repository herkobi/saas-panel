<?php

namespace App\Http\Controllers\Tenant;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Space;
use App\Http\Controllers\Controller;
use App\Services\Tenant\SpaceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Tenant\Space\SpaceCreateRequest;
use App\Http\Requests\Tenant\Space\SpaceUpdateRequest;

class SpaceController extends Controller
{
    public function __construct(protected SpaceService $spaceService)
    {
    }

    /**
     * Display a listing of spaces.
     */
    public function index(): Response
    {
        // Space listesini görüntüleme yetkisi kontrolü
        $this->authorize('viewAny', Space::class);

        $spaces = $this->spaceService->getAllSpaces();

        return Inertia::render('tenant/spaces/Index', [
            'spaces' => $spaces->map(function ($space) {
                return [
                    'id' => $space->id,
                    'name' => $space->name,
                    'color' => $space->color,
                    'links_count' => $space->links_count,
                    'created_at' => $space->created_at->format('Y-m-d'),
                ];
            }),
            'pagination' => [
                'current_page' => $spaces->currentPage(),
                'last_page' => $spaces->lastPage(),
                'total' => $spaces->total(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new space.
     */
    public function create(): Response
    {
        // Space oluşturma yetkisi kontrolü
        $this->authorize('create', Space::class);

        return Inertia::render('tenant/spaces/Create');
    }

    /**
     * Store a newly created space in storage.
     */
    public function store(SpaceCreateRequest $request): RedirectResponse
    {
        // Space oluşturma yetkisi kontrolü
        $this->authorize('create', Space::class);

        $created = $this->spaceService->createSpace($request->validated());
        return $created
            ? Redirect::route('app.spaces')->with('success', 'Alan başarıyla oluşturuldu.')
            : Redirect::back()->with('error', 'Alan oluşturulurken bir hata oluştu, lütfen tekrar deneyiniz.');
    }

    /**
     * Show the form for editing the specified space.
     */
    public function edit(Space $space): Response
    {
        // Space düzenleme yetkisi kontrolü
        $this->authorize('update', $space);

        return Inertia::render('tenant/spaces/Edit', [
            'space' => [
                'id' => $space->id,
                'name' => $space->name,
                'color' => $space->color,
                'created_at' => $space->created_at->format('Y-m-d'),
            ]
        ]);
    }

    /**
     * Update the specified space in storage.
     */
    public function update(SpaceUpdateRequest $request, Space $space): RedirectResponse
    {
        // Space güncelleme yetkisi kontrolü
        $this->authorize('update', $space);

        $updated = $this->spaceService->updateSpace($space->id, $request->validated());
        return $updated
            ? Redirect::route('app.spaces')->with('success', 'Alan başarıyla güncellendi.')
            : Redirect::back()->with('error', 'Alan güncellenirken bir hata oluştu, lütfen tekrar deneyiniz.');
    }

    /**
     * Remove the specified space from storage.
     */
    public function destroy(Space $space): RedirectResponse
    {
        // Space silme yetkisi kontrolü
        $this->authorize('delete', $space);

        $deleted = $this->spaceService->deleteSpace($space->id);
        return $deleted
            ? Redirect::route('app.spaces')->with('success', 'Alan başarıyla silindi.')
            : Redirect::route('app.spaces')->with('error', 'Alan silinirken bir hata oluştu, lütfen tekrar deneyiniz.');
    }
}
