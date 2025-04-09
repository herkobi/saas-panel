<?php

namespace App\Services\Admin;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContractService
{
    /**
     * Tüm sayfaları getir
     */
    public function getAllContracts(): Collection
    {
        return Contract::latest()->get();
    }

    /**
     * Sayfalanmış sayfaları getir
     */
    public function getPaginatedContracts(int $perContract = 15): LengthAwarePaginator
    {
        return Contract::latest()->paginate($perContract);
    }

    /**
     * Aktif sayfaları getir
     */
    public function getActiveContracts(): Collection
    {
        return Contract::where('status', true)
            ->orderBy('title')
            ->get();
    }

    /**
     * Sözleşmeyi slug'a göre getir (site görünümü için)
     */
    public function getContractBySlug(string $slug): ?Contract
    {
        return Contract::where('slug', $slug)
            ->where('status', true)
            ->first();
    }

    /**
     * Yeni sözleşme oluştur
     */
    public function create(array $data): Contract
    {
        return Contract::create($data);
    }

    /**
     * Sözleşmeyi güncelle
     */
    public function update(Contract $contract, array $data): Contract
    {
        $contract->update($data);
        return $contract;
    }

    /**
     * Sözleşmeyi sil
     */
    public function delete(Contract $contract): bool
    {
        return $contract->delete();
    }
}
