<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class InvoiceRepository extends BaseRepository
{
    protected $model = Invoice::class;
    protected $detailModel = InvoiceDetail::class;

    public function createInvoiceDetail(array $data)
    {
        return $this->detailModel::create($data);
    }

    public function updateInvoiceDetail(string $id, array $data): InvoiceDetail
    {
        $invoiceDetail = $this->getInvoiceDetail($id);
        $invoiceDetail->update([
            'title' => $data['title'],
            'invoiceName' => $data['invoiceName'],
            'taxNumber' => $data['taxNumber'],
            'taxOffice' => $data['taxOffice'],
            'mersis' => $data['mersis'],
            'address' => $data['address'],
            'zipCode' => $data['zipCode'],
            'state' => $data['state'],
            'city' => $data['city'],
            'country' => $data['country'],
            'email' => $data['email'],
            'kep' => $data['kep'],
            'phone' => $data['phone'],
        ]);

        return $invoiceDetail;
    }

    public function deleteInvoiceDetail(string $id): bool|null
    {
        $invoiceDetail = $this->getInvoiceDetail($id);
        if($invoiceDetail)
        {
            return $invoiceDetail->delete();
        }
        return false;
    }

    public function getInvoiceDetail(string $id): InvoiceDetail
    {
        return $this->detailModel::findOrFail($id);
    }

    public function getUserData(): LengthAwarePaginator
    {
        return $this->detailModel::defaultPagination();
    }
}
