<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait TenantFileUpload
{
    /**
     * Tenant'a ait public klasöre dosya yükler
     *
     * @param UploadedFile $file Yüklenecek dosya
     * @param string|null $directory Klasör yolu (opsiyonel)
     * @param string|null $filename Dosya adı (opsiyonel)
     * @return string|false Dosya yolu veya hata durumunda false
     */
    public function uploadTenantFile(UploadedFile $file, ?string $directory = null, ?string $filename = null)
    {
        if (!Auth::check() || !Auth::user()->tenant) {
            return false;
        }

        $tenant = Auth::user()->tenant;
        $tenantPath = $tenant->getPublicPath();

        // Eğer alt dizin belirtilmişse ekle
        if ($directory) {
            $tenantPath .= '/' . ltrim($directory, '/');
        }

        // Dosya adı belirtilmemişse orijinal dosya adını kullan ve SEO uyumlu hale getir
        if (!$filename) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($originalName) . '.' . $extension;
        }

        // Dosyayı yükle
        $path = $file->storeAs($tenantPath, $filename, 'public');

        return $path ? $filename : false;
    }

    /**
     * Tenant'a ait private klasöre dosya yükler
     *
     * @param UploadedFile $file Yüklenecek dosya
     * @param string|null $directory Klasör yolu (opsiyonel)
     * @param string|null $filename Dosya adı (opsiyonel)
     * @return string|false Dosya yolu veya hata durumunda false
     */
    public function uploadTenantPrivateFile(UploadedFile $file, ?string $directory = null, ?string $filename = null)
    {
        if (!Auth::check() || !Auth::user()->tenant) {
            return false;
        }

        $tenant = Auth::user()->tenant;
        $tenantPath = 'tenants/' . $tenant->id . '/private';

        // Eğer private_path settings içinde tanımlanmışsa onu kullan
        if (isset($tenant->settings['private_path'])) {
            $tenantPath = $tenant->settings['private_path'];
        } else {
            // Tanımlanmamışsa oluştur ve kaydet
            $folderName = 'folder_' . Str::random(8);
            $settings = $tenant->settings ?: [];
            $settings['private_path'] = $folderName;
            $tenant->settings = $settings;
            $tenant->save();

            $tenantPath = $folderName;

            // Private klasörü oluştur
            Storage::makeDirectory($tenantPath);
        }

        // Eğer alt dizin belirtilmişse ekle
        if ($directory) {
            $tenantPath .= '/' . ltrim($directory, '/');
        }

        // Dosya adı belirtilmemişse orijinal dosya adını kullan ve SEO uyumlu hale getir
        if (!$filename) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($originalName) . '.' . $extension;
        }

        // Dosyayı yükle (private olduğu için 'local' disk kullan)
        $path = $file->storeAs($tenantPath, $filename);

        return $path ? $filename : false;
    }

    /**
     * Tenant'a ait public klasörden dosya siler
     *
     * @param string $filename Silinecek dosya adı
     * @param string|null $directory Klasör yolu (opsiyonel)
     * @return bool Başarılı olup olmadığı
     */
    public function deleteTenantFile(string $filename, ?string $directory = null): bool
    {
        if (!Auth::check() || !Auth::user()->tenant) {
            return false;
        }

        $tenant = Auth::user()->tenant;
        $tenantPath = $tenant->getPublicPath();

        // Eğer alt dizin belirtilmişse ekle
        if ($directory) {
            $tenantPath .= '/' . ltrim($directory, '/');
        }

        // Dosyayı sil
        return Storage::disk('public')->delete($tenantPath . '/' . $filename);
    }

    /**
     * Tenant'a ait private klasörden dosya siler
     *
     * @param string $filename Silinecek dosya adı
     * @param string|null $directory Klasör yolu (opsiyonel)
     * @return bool Başarılı olup olmadığı
     */
    public function deleteTenantPrivateFile(string $filename, ?string $directory = null): bool
    {
        if (!Auth::check() || !Auth::user()->tenant) {
            return false;
        }

        $tenant = Auth::user()->tenant;

        // Tenant'ın private klasör yolunu al
        $tenantPath = isset($tenant->settings['private_path'])
            ? $tenant->settings['private_path']
            : 'tenants/' . $tenant->id . '/private';

        // Eğer alt dizin belirtilmişse ekle
        if ($directory) {
            $tenantPath .= '/' . ltrim($directory, '/');
        }

        // Dosyayı sil (private olduğu için 'local' disk kullan)
        return Storage::delete($tenantPath . '/' . $filename);
    }

    /**
     * Private dosyanın içeriğini getirir
     *
     * @param string $filename Dosya adı
     * @param string|null $directory Klasör yolu (opsiyonel)
     * @return string|null Dosya içeriği veya null
     */
    public function getTenantPrivateFile(string $filename, ?string $directory = null): ?string
    {
        if (!Auth::check() || !Auth::user()->tenant) {
            return null;
        }

        $tenant = Auth::user()->tenant;

        // Tenant'ın private klasör yolunu al
        $tenantPath = isset($tenant->settings['private_path'])
            ? $tenant->settings['private_path']
            : 'tenants/' . $tenant->id . '/private';

        // Eğer alt dizin belirtilmişse ekle
        if ($directory) {
            $tenantPath .= '/' . ltrim($directory, '/');
        }

        $filePath = $tenantPath . '/' . $filename;

        // Dosya var mı kontrol et
        if (!Storage::exists($filePath)) {
            return null;
        }

        // Dosya içeriğini oku ve dön
        return Storage::get($filePath);
    }
}
