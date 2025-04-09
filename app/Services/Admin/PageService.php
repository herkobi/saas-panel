<?php

namespace App\Services\Admin;

use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PageService
{
    /**
     * Tüm sayfaları getir
     */
    public function getAllPages(): Collection
    {
        return Page::latest()->get();
    }

    /**
     * Sayfalanmış sayfaları getir
     */
    public function getPaginatedPages(int $perPage = 15): LengthAwarePaginator
    {
        return Page::latest()->paginate($perPage);
    }

    /**
     * Aktif sayfaları getir
     */
    public function getActivePages(): Collection
    {
        return Page::where('status', true)
            ->orderBy('title')
            ->get();
    }

    /**
     * Sayfa slug'a göre getir (site görünümü için)
     */
    public function getPageBySlug(string $slug): ?Page
    {
        return Page::where('slug', $slug)
            ->where('status', true)
            ->first();
    }

    /**
     * Yeni sayfa oluştur
     */
    public function create(array $data): Page
    {
        return Page::create($data);
    }

    /**
     * Sayfayı güncelle
     */
    public function update(Page $page, array $data): Page
    {
        $page->update($data);
        return $page;
    }

    /**
     * Sayfayı sil
     */
    public function delete(Page $page): bool
    {
        return $page->delete();
    }
}
