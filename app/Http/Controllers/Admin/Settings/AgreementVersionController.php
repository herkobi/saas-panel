<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\AgreementVersion\AgreementVersionCreateRequest;
use App\Http\Requests\Admin\Settings\AgreementVersion\AgreementVersionUpdateRequest;
use App\Actions\Admin\Settings\AgreementVersion\Create;
use App\Actions\Admin\Settings\AgreementVersion\Update;
use App\Actions\Admin\Settings\AgreementVersion\Delete;
use App\Actions\Admin\Settings\AgreementVersion\Publish;
use App\Enums\AgreementVersionStatus;
use App\Http\Requests\Admin\Settings\AgreementVersion\AgreementVersionPublishRequest;
use App\Services\Admin\Settings\AgreementService;
use App\Services\Admin\Settings\AgreementVersionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AgreementVersionController extends Controller
{
    protected $agreementService;
    protected $agreementVersionService;
    protected $create;
    protected $update;
    protected $delete;
    protected $publish;


    public function __construct(
        AgreementService $agreementService,
        AgreementVersionService $agreementVersionService,
        Create $create,
        Update $update,
        Delete $delete,
        Publish $publish,
    ) {
        $this->agreementService = $agreementService;
        $this->agreementVersionService = $agreementVersionService;
        $this->create = $create;
        $this->update = $update;
        $this->delete = $delete;
        $this->publish = $publish;
    }

    public function index(string $slug): View
    {
        $agreement = $this->agreementService->getAgreementBySlug($slug);
        return view('admin.settings.agreements.versions.index', [
            'agreement' => $agreement
        ]);
    }

    public function create(string $slug): View
    {
        $agreement = $this->agreementService->getAgreementBySlug($slug);
        $lastPublishedVersion = $agreement->latestVersion();

        return view('admin.settings.agreements.versions.create', [
            'agreement' => $agreement,
            'lastContent' => $lastPublishedVersion?->content ?? ''
        ]);
    }

    public function store(string $slug, AgreementVersionCreateRequest $request): RedirectResponse
    {
        $agreement = $this->agreementService->getAgreementBySlug($slug);
        $created = $this->create->execute($agreement->id, $request->validated());
        return $created
                ? Redirect::route('panel.settings.agreement.version.detail', ['agreement' => $agreement->slug])->with('success', 'Sözleşmenize ait versiyon başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Sözleşmenize ait versiyon oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit(string $slug, string $id): View|RedirectResponse
    {
        $agreement = $this->agreementService->getAgreementBySlug($slug);
        $agreementVersion = $this->agreementVersionService->getAgreementVersionById($id);

        if (!$agreementVersion->canBeEdited()) {
            return Redirect::back()->with('error', 'Bu versiyon düzenlenemez. Sadece taslak durumundaki versiyonlar düzenlenebilir.');
        }

        return view('admin.settings.agreements.versions.edit', [
            'agreement' => $agreement,
            'agreementVersion' => $agreementVersion
        ]);
    }

    public function update(string $agreement, string $version, AgreementVersionUpdateRequest $request): RedirectResponse
    {
        $agreementVersion = $this->agreementVersionService->getAgreementVersionById($version);

        if (!$agreementVersion->canBeEdited()) {
            return Redirect::back()->with('error', 'Bu versiyon düzenlenemez. Sadece taslak durumundaki versiyonlar düzenlenebilir.');
        }

        $updated = $this->update->execute($version, $request->validated());

        return $updated
                ? Redirect::route('panel.settings.agreement.version.detail', ['agreement' => $agreement])->with('success', 'Sözleşme versiyonuna ait içerik başarılı bir şekilde güncellendi')
                : Redirect::back()->with('error', 'Sözleşme versiyonuna ait içerik güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy(string $agreement, string $version): RedirectResponse
    {
        $agreementVersion = $this->agreementVersionService->getAgreementVersionById($version);

        if ($agreementVersion->status !== AgreementVersionStatus::DRAFT) {
            return Redirect::back()->with('error', 'Bu versiyon silinemez. Sadece taslak durumundaki versiyonlar silinebilir.');
        }

        $deleted = $this->delete->execute($version);

        return $deleted
                ? Redirect::route('panel.settings.agreement.version.detail', ['agreement' => $agreement])->with('success', 'Sözleşme versiyonu başarılı bir şekilde silindi')
                : Redirect::back()->with('error', 'Sözleşme versiyonu silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function publish(string $agreement, string $version, AgreementVersionPublishRequest $request): RedirectResponse
    {
        $agreementVersion = $this->agreementVersionService->getAgreementVersionById($version);

        if ($agreementVersion->status !== AgreementVersionStatus::DRAFT) {
            return Redirect::back()->with('error', 'Bu versiyon yayınlanamaz. Sadece taslak durumundaki versiyonlar yayınlanabilir.');
        }

        $published = $this->publish->execute($version, $request->validated());

        return $published
                ? Redirect::route('panel.settings.agreement.version.detail', ['agreement' => $agreement])->with('success', 'Sözleşme versiyonu başarılı bir şekilde yayınlandı.')
                : Redirect::back()->with('error', 'Sözleşme versiyonu yayınlanırken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function show(string $slug, string $id): View|RedirectResponse
    {
        $agreement = $this->agreementService->getAgreementBySlug($slug);
        $agreementVersion = $this->agreementVersionService->getAgreementVersionById($id);

        return view('admin.settings.agreements.versions.show', [
            'agreementVersion' => $agreementVersion
        ]);
    }
}
