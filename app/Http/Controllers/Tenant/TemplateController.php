<?php

namespace App\Http\Controllers\Tenant;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;

class TemplateController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Tenant template.
     */
    public function index(): Response
    {
        return Inertia::render('tenant/template/Index');
    }
}
