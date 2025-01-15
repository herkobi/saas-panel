<?php

namespace App\Http\Controllers\Admin\Tools;

use App\Http\Controllers\Controller;
use App\Services\Admin\Tools\TaxService;
use App\Services\Admin\Tools\CountryService;
use App\Actions\Admin\Tools\Tax\Create;
use App\Actions\Admin\Tools\Tax\Update;
use App\Actions\Admin\Tools\Tax\Delete;
use App\Http\Requests\Admin\Tools\Tax\TaxUpdateRequest;
use App\Http\Requests\Admin\Tools\Tax\TaxCreateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TaxController extends Controller
{
    protected $taxService;
    protected $countryService;
    protected $createTax;
    protected $updateTax;
    protected $deleteTax;

    public function __construct(
        TaxService $taxService,
        CountryService $countryService,
        Create $createTax,
        Update $updateTax,
        Delete $deleteTax
    ) {
        $this->taxService = $taxService;
        $this->countryService = $countryService;
        $this->createTax = $createTax;
        $this->updateTax = $updateTax;
        $this->deleteTax = $deleteTax;
    }

    public function index(): View
    {
        $taxes = $this->taxService->getAllTaxes();
        $countries = $this->countryService->getAllCountries();
        return view('admin.tools.config.taxes.index', [
            'taxes' => $taxes,
            'countries' => $countries
        ]);
    }

    public function create(): View
    {
        $countries = $this->countryService->getActiveCountries();
        return view('admin.tools.config.taxes.create', [
            'countries' => $countries
        ]);
    }

    public function store(TaxCreateRequest $request): RedirectResponse
    {
        $created = $this->createTax->execute($request->validated());
        return $created
                ? Redirect::route('panel.tools.config.taxes')->with('success', 'Vergi oranı başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Vergi oranı oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit($id): View
    {
        $tax = $this->taxService->getTaxById($id);
        $countries = $this->countryService->getActiveCountries();
        return view('admin.tools.config.taxes.edit', [
            'tax' => $tax,
            'countries' => $countries
        ]);
    }

    public function update(TaxUpdateRequest $request, string $id): RedirectResponse
    {
        $updated = $this->updateTax->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.tools.config.taxes')->with('success', 'Vergi oranı başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Vergi oranı güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy($id): RedirectResponse
    {
        $deleted = $this->deleteTax->execute($id);
        return $deleted
                ? Redirect::route('panel.tools.config.taxes')->with('success', 'Vergi oranı başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'Vergi oranı silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
