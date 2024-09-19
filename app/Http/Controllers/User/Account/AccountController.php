<?php

namespace App\Http\Controllers\User\Account;

use App\Actions\User\InvoiceDetail\Create;
use App\Actions\User\InvoiceDetail\Delete;
use App\Actions\User\InvoiceDetail\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Account\InvoiceDetailCreateRequest;
use App\Http\Requests\User\Account\InvoiceDetailUpdateRequest;
use App\Services\User\Account\InvoiceService;
use Illuminate\View\View;
use App\Traits\AuthUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class AccountController extends Controller
{
    use AuthUser;

    protected $create;
    protected $update;
    protected $delete;
    protected $invoiceService;

    public function __construct(
        Create $create,
        Update $update,
        Delete $delete,
        InvoiceService $invoiceService,
    ) {
        $this->create = $create;
        $this->update = $update;
        $this->delete = $delete;
        $this->invoiceService = $invoiceService;
    }

    public function index(): View
    {
        return view('user.account.index');
    }

    public function payments(): View
    {
        return view('user.account.payments');
    }

    public function invoices(): View
    {
        return view('user.account.invoices');
    }

    public function invoiceDetail(): View
    {
        $invoicedetails = $this->invoiceService->getUserData();
        return view('user.account.invoicedetail.index', [
            'invoicedetails' => $invoicedetails
        ]);
    }

    public function newInvoiceDetail(): View
    {
        return view('user.account.invoicedetail.create');
    }

    public function editInvoiceDetail(string $id): View
    {
        $detail = $this->invoiceService->getInvoiceDetail($id);
        return view('user.account.invoicedetail.edit', [
            'detail' => $detail
        ]);
    }

    public function updateInvoiceDetail(InvoiceDetailUpdateRequest $request, $id): RedirectResponse
    {
        $updated = $this->update->execute($id, $request->validated());
        return $updated
                ? Redirect::route('app.account.invoicedetail')->with('success', 'Fatura bilginiz başarılı bir şekilde güncellendi.')
                : Redirect::route('app.account.invoicedetail')->with('error', 'Fatura bilgisi güncellenirken bir sorun oluştu, lütfen tekrar deneyiniz.');
    }

    public function storeInvoiceDetail(InvoiceDetailCreateRequest $request): RedirectResponse
    {
        $created = $this->create->execute($request->validated());
        return $created
                ? Redirect::route('app.account.invoicedetail')->with('success', 'Fatura bilginiz başarılı bir şekilde kayıt edildi.')
                : Redirect::route('app.account.invoicedetail')->with('error', 'Fatura bilginiz kayıt edilirken bir sorun oluştu, lütfen tekrar deneyiniz.');
    }

    public function deleteInvoiceDetail(string $id): RedirectResponse
    {
        $deleted = $this->delete->execute($id);
        return $deleted
                ? Redirect::route('app.account.invoicedetail')->with('success', 'Fatura bilgisi başarılı bir şekilde silindi.')
                : Redirect::route('app.account.invoicedetail')->with('error', 'Fatura bilgisi silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
 }
