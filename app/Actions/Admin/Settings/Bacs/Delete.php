<?php

namespace App\Actions\Admin\Settings\Bacs;

use App\Services\Admin\Settings\BacsService;
use App\Events\Admin\Settings\Bacs\Delete as Event;
use App\Models\Bacs;
use App\Traits\AuthUser;

class Delete
{
    use AuthUser;

    protected $bacsService;

    public function __construct(BacsService $bacsService)
    {
        $this->bacsService = $bacsService;
        $this->initializeAuthUser();
    }

    public function execute(string $id): Bacs
    {
        $bacs = $this->bacsService->getBacsById($id);
        $this->bacsService->deleteBacs($id);
        event(new Event($bacs, $this->user));
        return $bacs;
    }
}
