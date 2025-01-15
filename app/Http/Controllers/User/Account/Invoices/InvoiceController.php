<?php

namespace App\Http\Controllers\User\Account\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(): View
    {
        return view('user.account.invoices.index');
    }
 }
