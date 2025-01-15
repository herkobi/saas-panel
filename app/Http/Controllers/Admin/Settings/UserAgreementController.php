<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Services\Admin\Settings\AgreementService;
use App\Actions\Admin\Settings\Agreement\Create;
use App\Actions\Admin\Settings\Agreement\Update;
use App\Actions\Admin\Settings\Agreement\Delete;
use App\Enums\UserType;
use App\Http\Requests\Admin\Settings\Agreement\AgreementUpdateRequest;
use App\Http\Requests\Admin\Settings\Agreement\AgreementCreateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class UserAgreementController extends Controller
{
    protected $agreementService;
    protected $createAgreement;
    protected $updateAgreement;
    protected $deleteAgreement;


    public function __construct(
        AgreementService $agreementService,
        Create $createAgreement,
        Update $updateAgreement,
        Delete $deleteAgreement
    ) {
        $this->agreementService = $agreementService;
        $this->createAgreement = $createAgreement;
        $this->updateAgreement = $updateAgreement;
        $this->deleteAgreement = $deleteAgreement;
    }

    public function index(): View
    {
        $agreements = $this->agreementService->getUserAgreements();
        return view('admin.settings.agreements.user.index', [
            'agreements' => $agreements
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.agreements.user.create');
    }

    public function store(AgreementCreateRequest $request): RedirectResponse
    {
        $data = array_merge($request->validated(), ['user_type' => UserType::USER]);
        $created = $this->createAgreement->execute($data);
        return $created
                ? Redirect::route('panel.settings.agreements.user')->with('success', 'Sözleşme başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Sözleşme oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit($id): View
    {
        $agreement = $this->agreementService->getAgreementByIdAndType($id, UserType::USER);
        return view('admin.settings.agreements.user.edit', [
            'agreement' => $agreement
        ]);
    }

    public function update(AgreementUpdateRequest $request, $id): RedirectResponse
    {
        $data = array_merge($request->validated(), ['user_type' => UserType::USER]);
        $updated = $this->updateAgreement->execute($id, $data);
        return $updated
                ? Redirect::route('panel.settings.agreements.user')->with('success', 'Sözleşme başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Sözleşme güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy($id): RedirectResponse
    {
        $deleted = $this->deleteAgreement->execute($id);
        return $deleted
                ? Redirect::route('panel.settings.agreements.user')->with('success', 'Sözleşme başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'Sözleşme silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
