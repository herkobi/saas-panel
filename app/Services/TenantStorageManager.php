<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;

class TenantStorageManager
{
    public function getDisk()
    {
        // Tenant'ı bul
        $tenant = Tenant::find(session()->get('tenant_id'));

        return Storage::build([
            'driver' => 'local',
            'root' => storage_path("app/private/tenants/{$tenant->storage_folder}"),
            'throw' => false,
        ]);
    }

    public function ensureTenantDirectoryExists()
    {
        $tenant = Tenant::find(session()->get('tenant_id'));
        $path = storage_path("app/private/tenants/{$tenant->storage_folder}");

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }

    // Tenant spesifik dosya yükleme metodu
    public function storeFile($file, $path = '')
    {
        $tenant = Tenant::find(session()->get('tenant_id'));
        $tenantPath = "private/tenants/{$tenant->storage_folder}/{$path}";

        return Storage::putFile($tenantPath, $file);
    }

    // Ödeme dekontu yükleme metodu
    public function storePaymentDocument($file, $order)
    {
        $path = "private/tenants/{$order->tenant->storage_folder}/shared/payments";

        // Dizinin var olduğundan emin ol
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }

        return Storage::putFile($path, $file);
    }

    // Tenant spesifik dosya silme metodu
    public function deleteFile($path)
    {
        $tenant = Tenant::find(session()->get('tenant_id'));
        $tenantPath = "private/tenants/{$tenant->storage_folder}/{$path}";

        return Storage::delete($tenantPath);
    }
}
