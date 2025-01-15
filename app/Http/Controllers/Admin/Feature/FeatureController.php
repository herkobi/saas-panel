<?php

namespace App\Http\Controllers\Admin\Feature;

use App\Http\Controllers\Controller;
use App\Services\Admin\Feature\FeatureService;
use App\Services\Admin\Tools\CurrencyService;
use App\Actions\Admin\Feature\Create;
use App\Actions\Admin\Feature\Restore;
use App\Actions\Admin\Feature\Force;
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
    protected $currencyService;
    protected $createFeature;
    protected $updateFeature;
    protected $deleteFeature;
    protected $restoreFeature;
    protected $forceFeature;

    public function __construct(
        FeatureService $featureService,
        CurrencyService $currencyService,
        Create $createFeature,
        Update $updateFeature,
        Delete $deleteFeature,
        Restore $restoreFeature,
        Force $forceFeature,
    ) {
        $this->featureService = $featureService;
        $this->currencyService = $currencyService;
        $this->createFeature = $createFeature;
        $this->updateFeature = $updateFeature;
        $this->deleteFeature = $deleteFeature;
        $this->restoreFeature = $restoreFeature;
        $this->forceFeature = $forceFeature;
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
        $currencies = $this->currencyService->getActiveCurrencies();
        return view('admin.features.create', [
            'currencies' => $currencies
        ]);
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
        $currencies = $this->currencyService->getActiveCurrencies();
        return view('admin.features.edit', [
            'feature' => $feature,
            'currencies' => $currencies
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

    public function restore($id): RedirectResponse
    {
        $restored = $this->restoreFeature->execute($id);
        return $restored
                ? Redirect::route('panel.features')->with('success', 'Özellik başarılı bir şekilde geri alındı.')
                : Redirect::back()->with('error', 'Özellik geri alınırken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function force($id): RedirectResponse
    {
        $forced = $this->forceFeature->execute($id);
        return $forced
                ? Redirect::route('panel.features')->with('success', 'Özellik başarılı bir şekilde tamemen silindi.')
                : Redirect::back()->with('error', 'Özellik silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
