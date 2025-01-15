<?php

namespace App\Actions\Admin\Settings\Bacs;

use App\Services\Admin\Settings\BacsService;
use App\Events\Admin\Settings\Bacs\Update as Event;
use App\Models\Bacs;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $bacsService;

    public function __construct(BacsService $bacsService)
    {
        $this->bacsService = $bacsService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): Bacs
    {
        $oldBacs = $this->bacsService->getBacsById($id);
        $bacs = $this->bacsService->updateBacs($id, $data);
        $newBacs = $this->bacsService->getBacsById($id);
        event(new Event($bacs, $this->user, $oldBacs, $newBacs));
        return $bacs;
    }
}
