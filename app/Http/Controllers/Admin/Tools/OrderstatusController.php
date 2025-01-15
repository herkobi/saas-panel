<?php

namespace App\Http\Controllers\Admin\Tools;

use App\Http\Controllers\Controller;
use App\Services\Admin\Tools\OrderstatusService;
use App\Services\Admin\Tools\CountryService;
use App\Actions\Admin\Tools\Orderstatus\Create;
use App\Actions\Admin\Tools\Orderstatus\Update;
use App\Actions\Admin\Tools\Orderstatus\Delete;
use App\Http\Requests\Admin\Tools\Orderstatus\OrderstatusUpdateRequest;
use App\Http\Requests\Admin\Tools\Orderstatus\OrderstatusCreateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class OrderstatusController extends Controller
{
    protected $orderstatusService;
    protected $countryService;
    protected $createOrderstatus;
    protected $updateOrderstatus;
    protected $deleteOrderstatus;

    public function __construct(
        OrderstatusService $orderstatusService,
        CountryService $countryService,
        Create $createOrderstatus,
        Update $updateOrderstatus,
        Delete $deleteOrderstatus
    ) {
        $this->orderstatusService = $orderstatusService;
        $this->countryService = $countryService;
        $this->createOrderstatus = $createOrderstatus;
        $this->updateOrderstatus = $updateOrderstatus;
        $this->deleteOrderstatus = $deleteOrderstatus;
    }

    public function index(): View
    {
        $orderstatuses = $this->orderstatusService->getAllOrderstatuses();
        $countries = $this->countryService->getAllCountries();
        return view('admin.tools.config.orderstatuses.index', [
            'orderstatuses' => $orderstatuses,
            'countries' => $countries
        ]);
    }

    public function create(): View
    {
        $countries = $this->countryService->getActiveCountries();
        return view('admin.tools.config.orderstatuses.create', [
            'countries' => $countries
        ]);
    }

    public function store(OrderstatusCreateRequest $request): RedirectResponse
    {
        $created = $this->createOrderstatus->execute($request->validated());
        return $created
                ? Redirect::route('panel.tools.config.orderstatuses')->with('success', 'Sipariş durumu tanımı başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Sipariş durumu tanımı oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit($id): View
    {
        $orderstatus = $this->orderstatusService->getOrderstatusById($id);
        $countries = $this->countryService->getActiveCountries();
        return view('admin.tools.config.orderstatuses.edit', [
            'orderstatus' => $orderstatus,
            'countries' => $countries
        ]);
    }

    public function update(OrderstatusUpdateRequest $request, $id): RedirectResponse
    {
        $updated = $this->updateOrderstatus->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.tools.config.orderstatuses')->with('success', 'Sipariş durumu tanımı başarılı bir şekilde güncellendi.')
                : Redirect::back()->with('error', 'Sipariş durumu tanımı güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy($id): RedirectResponse
    {
        $deleted = $this->deleteOrderstatus->execute($id);
        return $deleted
                ? Redirect::route('panel.tools.config.orderstatuses')->with('success', 'Sipariş durumu tanımı başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'Sipariş durumu tanımı silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
