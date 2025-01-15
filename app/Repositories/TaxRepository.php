<?php

namespace App\Repositories;

use App\Enums\Status;
use App\Models\Tax;
use Illuminate\Database\Eloquent\Collection;

class TaxRepository extends BaseRepository
{
    protected $model = Tax::class;

    public function getActiveTaxes(): Collection
    {
        return Tax::where('status', Status::ACTIVE)
            ->orderBy('title', 'asc')
            ->get();
    }

    public function getAllTaxes(): Collection
    {
        return Tax::with(['regions.country', 'regions.state'])
            ->orderBy('title', 'asc')
            ->get();
    }

    public function createTax(array $data): Tax
    {
        $tax = $this->model::create([
            'status' => $data['status'],
            'title' => $data['title'],
            'code' => $data['code'],
            'value' => $data['value']
        ]);

        // Bölgeleri ekle
        if (isset($data['regions'])) {
            foreach ($data['regions'] as $region) {
                $tax->regions()->create([
                    'country_id' => $region['country_id'],
                    'state_id' => $region['state_id'] ?? null // Eyalet seçilmediyse null
                ]);
            }
        }

        return $tax;
    }

    public function updateTax(string $id, array $data): Tax
    {
        $tax = $this->getById($id);

        $tax->update([
            'status' => $data['status'],
            'title' => $data['title'],
            'code' => $data['code'],
            'value' => $data['value']
        ]);

        // Bölgeleri güncelle
        if (isset($data['regions'])) {
            // Mevcut bölgeleri sil
            $tax->regions()->delete();

            // Yeni bölgeleri ekle
            foreach ($data['regions'] as $region) {
                $tax->regions()->create([
                    'country_id' => $region['country_id'],
                    'state_id' => $region['state_id'] ?? null
                ]);
            }
        }

        return $tax;
    }

    public function deleteTax(string $id): bool|null
    {
        $tax = $this->getById($id);
        $tax->regions()->delete();
        return $tax->delete();
    }

    /**
     * Get tax rate for specific country and state (optional)
     *
     * @param string $countryId Country ID
     * @param string|null $stateId Optional state ID
     * @return float Tax rate percentage
     */
    public function getTaxRateByRegion(string $countryId, ?string $stateId = null): float
    {
        // Önce eyalete özel vergi var mı diye bak
        if ($stateId) {
            $tax = $this->model::where('status', Status::ACTIVE)
                ->whereHas('regions', function($query) use ($countryId, $stateId) {
                    $query->where('country_id', $countryId)
                        ->where('state_id', $stateId);
                })
                ->first();

            if ($tax) {
                return (float)$tax->value;
            }
        }

        // Eyalete özel yoksa ülke genelinde vergi var mı bak
        $tax = $this->model::where('status', Status::ACTIVE)
            ->whereHas('regions', function($query) use ($countryId) {
                $query->where('country_id', $countryId)
                    ->whereNull('state_id');
            })
            ->first();

        return $tax ? (float)$tax->value : 0;
    }

    public function getTaxesByRegion(string $countryId, ?string $stateId = null): Collection
    {
        return $this->model::where('status', Status::ACTIVE)
            ->whereHas('regions', function($query) use ($countryId, $stateId) {
                $query->where('country_id', $countryId);
                if ($stateId) {
                    $query->where('state_id', $stateId);
                } else {
                    $query->whereNull('state_id');
                }
            })
            ->get();
    }
}
