<?php

namespace App\Repositories;

use App\Enums\Status;
use App\Models\Bacs;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class BacsRepository extends BaseRepository
{
    protected $model = Bacs::class;

    public function createBacs(array $data): Bacs
    {
        if (isset($data['logo'])) {
            $data['logo'] = $data['logo']->store('bacs', 'public');
        }

        $bacs = $this->model::create([
            'currency_id' => $data['currency_id'], // Artık zorunlu alan
            'status' => $data['status'],
            'logo' => $data['logo'] ?? null,
            'bank_name' => $data['bank_name'],
            'account_holder' => $data['account_holder'],
            'account_number' => $data['account_number'],
            'iban' => $data['iban'],
            'swift' => $data['swift'] ?? null
        ]);

        return $bacs;
    }

    public function updateBacs(string $id, array $data): Bacs
    {

        $bacs = $this->getById($id);

        // Logo işlemi
        if (isset($data['logo'])) {
            // Eski logo varsa sil
            if ($bacs->logo) {
                Storage::disk('public')->delete($bacs->logo);
            }
            $data['logo'] = $data['logo']->store('bacs', 'public');
        }

        // Bacs güncelle
        $bacs->update([
            'currency_id' => $data['currency_id'], // Artık zorunlu alan
            'status' => $data['status'],
            'logo' => $data['logo'] ?? $bacs->logo,
            'bank_name' => $data['bank_name'],
            'account_holder' => $data['account_holder'],
            'account_number' => $data['account_number'],
            'iban' => $data['iban'],
            'swift' => $data['swift'] ?? null
        ]);

        return $bacs;
    }

    public function getActive(): Collection
    {
        return $this->model::where('status', Status::ACTIVE->value)->get();
    }

    public function deleteBacs(string $id): bool|null
    {
        $bacs = $this->getById($id);

        // Logo varsa sil
        if ($bacs->logo) {
            Storage::disk('public')->delete($bacs->logo);
        }

        // Bacs'i sil
        return $bacs->delete();
    }

}
