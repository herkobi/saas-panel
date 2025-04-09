<?php

namespace App\Http\Controllers\Tenant;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Pixel;
use App\Enums\PixelType;
use App\Http\Controllers\Controller;
use App\Services\Tenant\PixelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Tenant\Pixel\PixelCreateRequest;
use App\Http\Requests\Tenant\Pixel\PixelUpdateRequest;

class PixelController extends Controller
{
    public function __construct(protected PixelService $pixelService)
    {
    }

    /**
     * Display a listing of pixels.
     */
    public function index(): Response
    {
        // Pixel listesini görüntüleme yetkisi kontrolü
        $this->authorize('viewAny', Pixel::class);

        $pixels = $this->pixelService->getAllPixels();

        return Inertia::render('tenant/pixels/Index', [
            'pixels' => $pixels->map(function ($pixel) {
                return [
                    'id' => $pixel->id,
                    'name' => $pixel->name,
                    'type' => $pixel->type->value,
                    'type_label' => ucfirst(str_replace('-', ' ', $pixel->type->value)),
                    'value' => $pixel->value,
                    'links_count' => $pixel->links_count,
                    'created_at' => $pixel->created_at->format('Y-m-d'),
                ];
            }),
            'pagination' => [
                'current_page' => $pixels->currentPage(),
                'last_page' => $pixels->lastPage(),
                'total' => $pixels->total(),
            ],
            'pixelTypes' => collect(PixelType::cases())->map(function ($type) {
                return [
                    'value' => $type->value,
                    'label' => ucfirst(str_replace('-', ' ', $type->value)),
                ];
            })
        ]);
    }

    /**
     * Show the form for creating a new pixel.
     */
    public function create(): Response
    {
        // Pixel oluşturma yetkisi kontrolü
        $this->authorize('create', Pixel::class);

        return Inertia::render('tenant/pixels/Create', [
            'pixelTypes' => collect(PixelType::cases())->map(function ($type) {
                return [
                    'value' => $type->value,
                    'label' => ucfirst(str_replace('-', ' ', $type->value)),
                ];
            })
        ]);
    }

    /**
     * Store a newly created pixel in storage.
     */
    public function store(PixelCreateRequest $request): RedirectResponse
    {
        // Pixel oluşturma yetkisi kontrolü
        $this->authorize('create', Pixel::class);

        $created = $this->pixelService->createPixel($request->validated());
        return $created
            ? Redirect::route('app.pixels')->with('success', 'Piksel başarıyla oluşturuldu.')
            : Redirect::back()->with('error', 'Piksel oluşturulurken bir hata oluştu, lütfen tekrar deneyiniz.');
    }

    /**
     * Show the form for editing the specified pixel.
     */
    public function edit(Pixel $pixel): Response
    {
        // Pixel düzenleme yetkisi kontrolü
        $this->authorize('update', $pixel);

        return Inertia::render('tenant/pixels/Edit', [
            'pixel' => [
                'id' => $pixel->id,
                'name' => $pixel->name,
                'type' => $pixel->type->value,
                'value' => $pixel->value,
                'created_at' => $pixel->created_at->format('Y-m-d'),
            ],
            'pixelTypes' => collect(PixelType::cases())->map(function ($type) {
                return [
                    'value' => $type->value,
                    'label' => ucfirst(str_replace('-', ' ', $type->value)),
                ];
            })
        ]);
    }

    /**
     * Update the specified pixel in storage.
     */
    public function update(PixelUpdateRequest $request, Pixel $pixel): RedirectResponse
    {
        // Pixel güncelleme yetkisi kontrolü
        $this->authorize('update', $pixel);

        $updated = $this->pixelService->updatePixel($pixel->id, $request->validated());
        return $updated
            ? Redirect::route('app.pixels')->with('success', 'Piksel başarıyla güncellendi.')
            : Redirect::back()->with('error', 'Piksel güncellenirken bir hata oluştu, lütfen tekrar deneyiniz.');
    }

    /**
     * Remove the specified pixel from storage.
     */
    public function destroy(Pixel $pixel): RedirectResponse
    {
        // Pixel silme yetkisi kontrolü
        $this->authorize('delete', $pixel);

        // Check if the pixel has links before deletion
        if ($pixel->links()->count() > 0) {
            return Redirect::route('app.pixels')
                ->with('error', 'Bu piksele bağlı linkler bulunduğu için silinemez.');
        }

        $deleted = $this->pixelService->deletePixel($pixel->id);
        return $deleted
            ? Redirect::route('app.pixels')->with('success', 'Piksel başarıyla silindi.')
            : Redirect::route('app.pixels')->with('error', 'Piksel silinirken bir hata oluştu, lütfen tekrar deneyiniz.');
    }
}
