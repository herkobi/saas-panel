<?php

namespace App\Http\Controllers\Admin\Tools;

use App\Http\Controllers\Controller;
use App\Services\Admin\Tools\StateService;
use App\Actions\Admin\Tools\State\Create;
use App\Actions\Admin\Tools\State\Update;
use App\Actions\Admin\Tools\State\Delete;
use App\Http\Requests\Admin\Tools\State\StateUpdateRequest;
use App\Http\Requests\Admin\Tools\State\StateCreateRequest;
use App\Services\Admin\Tools\CountryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class StateController extends Controller
{
    protected $stateService;
    protected $countryService;
    protected $createState;
    protected $updateState;
    protected $deleteState;


    public function __construct(
        StateService $stateService,
        CountryService $countryService,
        Create $createState,
        Update $updateState,
        Delete $deleteState
    ) {
        $this->stateService = $stateService;
        $this->countryService = $countryService;
        $this->createState = $createState;
        $this->updateState = $updateState;
        $this->deleteState = $deleteState;
    }

    public function index($country): View
    {
        $states = $this->stateService->getAllStates($country);
        $country = $this->countryService->getCountryById($country);
        return view('admin.tools.config.states.index', [
            'states' => $states,
            'country' => $country
        ]);
    }

    public function create($country): View
    {
        $country = $this->countryService->getCountryById($country);
        return view('admin.tools.config.states.create', [
            'country' => $country
        ]);
    }

    public function store(StateCreateRequest $request, $country): RedirectResponse
    {
        $created = $this->createState->execute($request->validated());
        return $created
                ? Redirect::route('panel.tools.config.states', $country)->with('success', 'Eyalet/Şehir başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Eyalet/Şehir oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit($id): View
    {
        $state = $this->stateService->getStateById($id);
        return view('admin.tools.config.states.edit', [
            'state' => $state,
            'country' => $state->country->name
        ]);
    }

    public function update(StateUpdateRequest $request, $id): RedirectResponse
    {
        $state = $this->stateService->getStateById($id);
        $updated = $this->updateState->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.tools.config.states', $state->country_id)->with('success', 'Eyalet/Şehir başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Eyalet/Şehir güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy($id): RedirectResponse
    {
        $deleted = $this->deleteState->execute($id);
        return $deleted
                ? Redirect::route('panel.tools.config.states', $deleted->country_id)->with('success', 'Eyalet/Şehir başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'Eyalet/Şehir silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
