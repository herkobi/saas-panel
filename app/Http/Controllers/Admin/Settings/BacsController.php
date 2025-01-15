<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Services\Admin\Settings\BacsService;
use App\Actions\Admin\Settings\Bacs\Create;
use App\Actions\Admin\Settings\Bacs\Update;
use App\Actions\Admin\Settings\Bacs\Delete;
use App\Http\Requests\Admin\Settings\Bacs\BacsUpdateRequest;
use App\Http\Requests\Admin\Settings\Bacs\BacsCreateRequest;
use App\Services\Admin\Tools\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BacsController extends Controller
{
    protected $bacService;
    protected $currencyService;
    protected $createBacs;
    protected $updateBacs;
    protected $deleteBacs;


    public function __construct(
        BacsService $bacService,
        CurrencyService $currencyService,
        Create $createBacs,
        Update $updateBacs,
        Delete $deleteBacs
    ) {
        $this->currencyService = $currencyService;
        $this->bacService = $bacService;
        $this->createBacs = $createBacs;
        $this->updateBacs = $updateBacs;
        $this->deleteBacs = $deleteBacs;
    }

    public function index(): View
    {
        $bacs = $this->bacService->getAllBacs();
        return view('admin.settings.payments.bacs.index', [
            'bacs' => $bacs
        ]);
    }

    public function create(): View
    {
        $currencies = $this->currencyService->getActiveCurrencies();
        return view('admin.settings.payments.bacs.create', [
            'currencies' => $currencies
        ]);
    }

    public function store(BacsCreateRequest $request): RedirectResponse
    {
        $created = $this->createBacs->execute($request->validated());
        return $created
                ? Redirect::route('panel.settings.payments.bacs')->with('success', 'EFT/Havale bilgisi başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'EFT/Havale bilgisi oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit($id): View
    {
        $bacs = $this->bacService->getBacsById($id);
        $currencies = $this->currencyService->getActiveCurrencies();
        return view('admin.settings.payments.bacs.edit', [
            'bacs' => $bacs,
            'currencies' => $currencies
        ]);
    }

    public function update(BacsUpdateRequest $request, $id): RedirectResponse
    {
        $updated = $this->updateBacs->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.settings.payments.bacs')->with('success', 'EFT/Havale bilgisi başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'EFT/Havale bilgisi güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy($id): RedirectResponse
    {
        $deleted = $this->deleteBacs->execute($id);
        return $deleted
                ? Redirect::route('panel.settings.payments.bacs')->with('success', 'EFT/Havale bilgisi başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'EFT/Havale bilgisi silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
