<?php

namespace App\Actions\User\Order;

use App\Models\Order;
use App\Models\Orderstatus;
use App\Services\OrderService;
use App\Services\TenantStorageManager;
use Illuminate\Http\UploadedFile;

class Upload
{
    protected $orderService;
    protected $storageManager;

    public function __construct(
        OrderService $orderService,
        TenantStorageManager $storageManager
    ) {
        $this->orderService = $orderService;
        $this->storageManager = $storageManager;
    }

    public function execute(string $id, UploadedFile $document): bool
    {
        // Önce order'ı al
        $order = Order::findOrFail($id);

        // TenantStorageManager'ı kullanarak dosyayı kaydet
        $path = $this->storageManager->storePaymentDocument($document, $order);

        // Order'ı güncelle - Hem dökümanı kaydet hem status'ü REVIEW yap
        $updated = $this->orderService->updateOrder($id, [
            'document' => $path,
            'orderstatus_id' => Orderstatus::where('code', 'REVIEW')->first()->id
        ]);

        return $updated ? true : false;
    }
}
