<?php

namespace App\Services\Tenant;

use App\Models\Pixel;
use Illuminate\Pagination\LengthAwarePaginator;

class PixelService
{
    protected $model = Pixel::class;

    public function __construct(protected PixelValidatorService $pixelValidator)
    {
        // HasTenant trait'i kullanıyorsa burada init olmalı
    }

    /**
     * Get all pixels with pagination and link count
     */
    public function getAllPixels(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model::withCount('links')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get pixel by ID
     */
    public function getPixelById(int $id): ?Pixel
    {
        return $this->model::find($id);
    }

    /**
     * Create a new pixel
     */
    public function createPixel(array $data): ?Pixel
    {
        // Pixel içeriğini validate et
        $validation = $this->pixelValidator->validatePixelCode($data['value']);

        // Güvenli değilse loglama yap ve kullanıcıyı uyar
        if (!$validation['is_safe'] && !config('link-safety.allow_unsafe_pixels', false)) {
            return null;
        }

        // Güvenliyse veya güvenli olmayan pixellere izin veriliyorsa oluştur
        return $this->model::create([
            'name' => $data['name'],
            'type' => $data['type'],
            'value' => $data['value'],
        ]);
    }

    /**
     * Update a pixel
     */
    public function updatePixel(int $id, array $data): bool
    {
        $pixel = $this->getPixelById($id);

        if (!$pixel) {
            return false;
        }

        // Pixel içeriği değiştiyse validate et
        if (isset($data['value']) && $data['value'] !== $pixel->value) {
            $validation = $this->pixelValidator->validatePixelCode($data['value']);

            // Güvenli değilse loglama yap ve kullanıcıyı uyar
            if (!$validation['is_safe'] && !config('link-safety.allow_unsafe_pixels', false)) {
                // Güvenli olmayan pixellere izin verilmiyorsa false döndür
                return false;
            }
        }

        return $pixel->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'value' => $data['value'],
        ]);
    }

    /**
     * Delete a pixel
     */
    public function deletePixel(int $id): bool
    {
        $pixel = $this->getPixelById($id);

        if (!$pixel) {
            return false;
        }

        return $pixel->delete();
    }
}
