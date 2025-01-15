<?php

namespace App\Services\Admin\Settings;

use App\Repositories\PaytrRepository;
use App\Models\Setting;

class PaytrService
{
    protected $repository;

    public function __construct(PaytrRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPaytrData()
    {
        return $this->repository->getPaytrData();
    }

    public function updatePaytrData(string $data): Setting
    {
        return $this->repository->updatePaytrData($data);
    }
}
