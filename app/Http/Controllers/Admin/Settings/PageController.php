<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Page\PageCreateRequest;
use App\Http\Requests\Admin\Settings\Page\PageUpdateRequest;
use App\Models\Admin\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::all();
        return view('admin.settings.pages.index', [
            'pages' => $pages
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.pages.create');
    }

    public function store(PageCreateRequest $request): RedirectResponse
    {
        $page = Page::create([
            'status' => $request->status,
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'text' => $request->text
        ]);

        return Redirect::route('panel.settings.pages')->with('success', __('admin/settings/pages.store.success'));
    }

    public function edit(Page $page): View
    {
        return view('admin.settings.pages.edit', [
            'page' => $page
        ]);
    }

    public function update(PageUpdateRequest $request, Page $page): RedirectResponse
    {

        $page->status = $request->status;
        $page->title = $request->title;
        $page->text = $request->text;
        $page->save();

        return Redirect::route('panel.settings.pages')->with('success', __('admin/settings/pages.update.success'));
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        return Redirect::route('panel.settings.pages')->with('success', __('admin/settings/pages.destroy.success'));
    }
}
