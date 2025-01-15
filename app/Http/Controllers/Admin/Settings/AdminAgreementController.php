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

class AdminAgreementController extends Controller
{
    protected $agreementService;
    protected $create;
    protected $update;
    protected $delete;


    public function __construct(
        AgreementService $agreementService,
        Create $create,
        Update $update,
        Delete $delete
    ) {
        $this->agreementService = $agreementService;
        $this->create = $create;
        $this->update = $update;
        $this->delete = $delete;
    }

    public function index(): View
    {
        $agreements = $this->agreementService->getAdminAgreements();
        return view('admin.settings.agreements.admin.index', [
            'agreements' => $agreements
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.agreements.create');
    }

    public function store(AgreementCreateRequest $request): RedirectResponse
    {
        $data = array_merge($request->validated(), ['user_type' => UserType::ADMIN]);
        $created = $this->create->execute($data);
        return $created
                ? Redirect::route('panel.settings.agreements.admin')->with('success', 'Sözleşme başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Sözleşme oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit($id): View
    {
        $agreement = $this->agreementService->getAgreementByIdAndType($id, UserType::ADMIN);
        return view('admin.settings.agreements.admin.edit', [
            'agreement' => $agreement
        ]);
    }

    public function update(AgreementUpdateRequest $request, $id): RedirectResponse
    {
        $data = array_merge($request->validated(), ['user_type' => UserType::ADMIN]);
        $updated = $this->update->execute($id, $data);
        return $updated
                ? Redirect::route('panel.settings.agreements.admin')->with('success', 'Sözleşme başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Sözleşme güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy($id): RedirectResponse
    {
        $deleted = $this->delete->execute($id);
        return $deleted
                ? Redirect::route('panel.settings.agreements.admin')->with('success', 'Sözleşme başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'Sözleşme silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function signatures(): View
    {
        $signatures = $this->agreementService->getAllSignatures();
        return view('admin.settings.agreements.signatures.index', [
            'signatures' => $signatures
        ]);
    }

    // Sözleşme özelinde imzaları listeleme
    public function agreementSignatures(string $id): View
    {
        $agreement = $this->agreementService->getAgreementById($id);
        $signatures = $this->agreementService->getAgreementSignatures($id);

        return view('admin.settings.agreements.signatures.agreement', [
            'agreement' => $agreement,
            'signatures' => $signatures
        ]);
    }
}
