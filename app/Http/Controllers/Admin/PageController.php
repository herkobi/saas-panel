<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Page\PageCreateRequest;
use App\Http\Requests\Admin\Page\PageUpdateRequest;
use App\Models\Page;
use App\Services\Admin\PageService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class PageController extends Controller
{
    public function __construct(protected PageService $pageService)
    {
    }

    /**
     * Display a listing of the pages.
     */
    public function index()
    {
        $pages = $this->pageService->getAllPages();

        return Inertia::render('admin/pages/Index', [
            'pages' => $pages->map(function ($page) {
                return [
                    'id' => $page->id,
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'summary' => $page->summary,
                    'status' => $page->status,
                    'created_at' => $page->created_at->format('Y-m-d'),
                ];
            }),
        ]);
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        return Inertia::render('admin/pages/Create');
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(PageCreateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->pageService->create($validated);

        return redirect()->route('panel.pages.index')
            ->with('success', 'Sayfa başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page)
    {
        return Inertia::render('admin/pages/Edit', [
            'page' => [
                'id' => $page->id,
                'title' => $page->title,
                'slug' => $page->slug,
                'summary' => $page->summary,
                'content' => $page->content,
                'status' => $page->status,
            ],
        ]);
    }

    /**
     * Update the specified page in storage.
     */
    public function update(PageUpdateRequest $request, Page $page): RedirectResponse
    {
        $validated = $request->validated();

        $this->pageService->update($page, $validated);

        return redirect()->route('panel.pages.index')
            ->with('success', 'Sayfa başarıyla güncellendi.');
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy(Page $page): RedirectResponse
    {
        $this->pageService->delete($page);

        return redirect()->route('panel.pages.index')
            ->with('success', 'Sayfa başarıyla silindi.');
    }
}
