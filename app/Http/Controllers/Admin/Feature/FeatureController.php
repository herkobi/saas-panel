<?php

namespace App\Http\Controllers\Admin\Feature;

use App\Http\Controllers\Controller;
use App\Services\Admin\Feature\FeatureService;
use App\Actions\Admin\Feature\Create;
use App\Actions\Admin\Feature\Update;
use App\Actions\Admin\Feature\Delete;
use App\Http\Requests\Admin\Feature\FeatureUpdateRequest;
use App\Http\Requests\Admin\Feature\FeatureCreateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class FeatureController extends Controller
{
    protected $featureService;
    protected $createFeature;
    protected $updateFeature;
    protected $deleteFeature;


    public function __construct(
        FeatureService $featureService,
        Create $createFeature,
        Update $updateFeature,
        Delete $deleteFeature
    ) {
        $this->featureService = $featureService;
        $this->createFeature = $createFeature;
        $this->updateFeature = $updateFeature;
        $this->deleteFeature = $deleteFeature;
    }

    public function index(): View
    {
        $features = $this->featureService->getAllFeatures();
        return view('admin.features.index', [
            'features' => $features
        ]);
    }

    public function create(): View
    {
        return view('admin.features.create');
    }

    public function store(FeatureCreateRequest $request): RedirectResponse
    {
        $created = $this->createFeature->execute($request->validated());
        return $created
                ? Redirect::route('panel.features')->with('success', 'Özellik başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Özellik oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit($id): View
    {
        $feature = $this->featureService->getFeatureById($id);
        return view('admin.features.edit', [
            'feature' => $feature
        ]);
    }

    public function update(FeatureUpdateRequest $request, $id): RedirectResponse
    {
        $updated = $this->updateFeature->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.features')->with('success', 'Özellik başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Özellik güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy($id): RedirectResponse
    {
        $deleted = $this->deleteFeature->execute($id);
        return $deleted
                ? Redirect::route('panel.features')->with('success', 'Özellik başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'Özellik silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
