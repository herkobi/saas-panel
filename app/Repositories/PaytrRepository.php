<?php

namespace App\Repositories;

use App\Facades\Setting as FacadesSetting;
use App\Models\Setting;

class PaytrRepository
{
    protected $operator = 'paytr';

    public function getPaytrData()
    {
        return Setting::where('key', $this->operator)->value('value') ?? "{}";
    }

    public function updatePaytrData(string $value): Setting
    {
        // Ana paytr verilerini kaydet
        $paytr = Setting::updateOrCreate(
            ['key' => $this->operator],
            ['value' => $value]
        );

        // Status'u ayrıca kaydet
        $data = json_decode($value, true);
        FacadesSetting::set('paytr_status', $data['status']);

        return $paytr;
    }
}
