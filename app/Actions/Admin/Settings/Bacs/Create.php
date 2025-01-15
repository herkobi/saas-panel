<?php

namespace App\Actions\Admin\Settings\Bacs;

use App\Models\Bacs;
use App\Services\Admin\Settings\BacsService;
use App\Events\Admin\Settings\Bacs\Create as Event;
use App\Traits\AuthUser;

class Create
{
    use AuthUser;

    protected $bacsService;

    public function __construct(BacsService $bacsService)
    {
        $this->bacsService = $bacsService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): Bacs
    {
        $bacs = $this->bacsService->createBacs($data);
        event(new Event($bacs, $this->user));
        return $bacs;
    }
}
