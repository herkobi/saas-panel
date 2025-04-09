<?php

namespace App\Http\Controllers\Tenant;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Content;
use App\Http\Controllers\Controller;
use App\Services\Tenant\ContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Tenant\Content\ContentCreateRequest;
use App\Http\Requests\Tenant\Content\ContentUpdateRequest;

class ContentController extends Controller
{
    public function __construct(protected ContentService $contentService)
    {
    }

    /**
     * Display a listing of contents.
     */
    public function index(): Response
    {
        // Content listesini görüntüleme yetkisi kontrolü
        $this->authorize('viewAny', Content::class);

        $contents = $this->contentService->getAllContents();

        return Inertia::render('tenant/contents/Index', [
            'contents' => $contents->map(function ($content) {
                return [
                    'id' => $content->id,
                    'name' => $content->name,
                    'color' => $content->color,
                    'links_count' => $content->links_count,
                    'created_at' => $content->created_at->format('Y-m-d'),
                ];
            }),
            'pagination' => [
                'current_page' => $contents->currentPage(),
                'last_page' => $contents->lastPage(),
                'total' => $contents->total(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new content.
     */
    public function create(): Response
    {
        // Content oluşturma yetkisi kontrolü
        $this->authorize('create', Content::class);

        return Inertia::render('tenant/contents/Create');
    }

    /**
     * Store a newly created content in storage.
     */
    public function store(ContentCreateRequest $request): RedirectResponse
    {
        // Content oluşturma yetkisi kontrolü
        $this->authorize('create', Content::class);

        $created = $this->contentService->createContent($request->validated());
        return $created
            ? Redirect::route('app.contents')->with('success', 'Alan başarıyla oluşturuldu.')
            : Redirect::back()->with('error', 'Alan oluşturulurken bir hata oluştu, lütfen tekrar deneyiniz.');
    }

    /**
     * Show the form for editing the specified content.
     */
    public function edit(Content $content): Response
    {
        // Content düzenleme yetkisi kontrolü
        $this->authorize('update', $content);

        return Inertia::render('tenant/contents/Edit', [
            'content' => [
                'id' => $content->id,
                'name' => $content->name,
                'color' => $content->color,
                'created_at' => $content->created_at->format('Y-m-d'),
            ]
        ]);
    }

    /**
     * Update the specified content in storage.
     */
    public function update(ContentUpdateRequest $request, Content $content): RedirectResponse
    {
        // Content güncelleme yetkisi kontrolü
        $this->authorize('update', $content);

        $updated = $this->contentService->updateContent($content->id, $request->validated());
        return $updated
            ? Redirect::route('app.contents')->with('success', 'Alan başarıyla güncellendi.')
            : Redirect::back()->with('error', 'Alan güncellenirken bir hata oluştu, lütfen tekrar deneyiniz.');
    }

    /**
     * Remove the specified content from storage.
     */
    public function destroy(Content $content): RedirectResponse
    {
        // Content silme yetkisi kontrolü
        $this->authorize('delete', $content);

        $deleted = $this->contentService->deleteContent($content->id);
        return $deleted
            ? Redirect::route('app.contents')->with('success', 'Alan başarıyla silindi.')
            : Redirect::route('app.contents')->with('error', 'Alan silinirken bir hata oluştu, lütfen tekrar deneyiniz.');
    }
}
