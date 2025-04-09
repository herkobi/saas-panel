<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Feature\FeatureCreateRequest;
use App\Http\Requests\Admin\Feature\FeatureUpdateRequest;
use App\Models\Feature;
use App\Services\Admin\FeatureService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class FeatureController extends Controller
{
    public function __construct(protected FeatureService $featureService)
    {
    }

    /**
     * Display a listing of the features.
     */
    public function index()
    {
        $features = $this->featureService->getAllFeatures();

        return Inertia::render('admin/features/Index', [
            'features' => $features->map(function ($feature) {
                return [
                    'id' => $feature->id,
                    'name' => $feature->name,
                    'slug' => $feature->slug,
                    'status' => $feature->status,
                    'plan_count' => $feature->plan_features_count,
                    'created_at' => $feature->created_at->format('Y-m-d'),
                ];
            }),
        ]);
    }

    /**
     * Show the form for editing the specified feature.
     */
    public function edit(Feature $feature)
    {
        return Inertia::render('admin/features/Edit', [
            'feature' => [
                'id' => $feature->id,
                'name' => $feature->name,
                'slug' => $feature->slug,
                'description' => $feature->description,
                'status' => $feature->status,
            ],
        ]);
    }

    /**
     * Store a newly created feature in storage.
     */
    public function store(FeatureCreateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->featureService->create($validated);

        return redirect()->route('panel.features.index')
            ->with('success', 'Özellik başarıyla oluşturuldu.');
    }

    /**
     * Update the specified feature in storage.
     */
    public function update(FeatureUpdateRequest $request, Feature $feature): RedirectResponse
    {
        $validated = $request->validated();

        $this->featureService->update($feature, $validated);

        return redirect()->route('panel.features.index')
            ->with('success', 'Özellik başarıyla güncellendi.');
    }

    /**
     * Remove the specified feature from storage.
     */
    public function destroy(Feature $feature): RedirectResponse
    {
        $deleted = $this->featureService->delete($feature);

        if (!$deleted) {
            return redirect()->route('panel.features.index')
                ->with('error', 'Özelliğin kullanıldığı plan mevcut. Lütfen önce onları kaldırınız.');
        }

        return redirect()->route('panel.features.index')
            ->with('success', 'Özellik başarıyla silindi.');
    }
}
