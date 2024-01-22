<?php

namespace App\Http\Controllers\Admin\Gateways;

use App\Http\Controllers\Controller;
use App\Models\Admin\Gateway;
use Illuminate\View\View;

class GatewayController extends Controller
{

    public function bac(): View
    {
        $gateways = Gateway::where('payment_id', 1)->get();
        $values = [];
        foreach ($gateways as $gateway) {
            if ($gateway->value) {
                $values[] = json_decode($gateway->value, true);
            }
        }

        return view('admin.gateways.bac', [
            'gateways' => $gateways,
            'values' => $values
        ]);
    }

    public function cc(): View
    {
        $gateways = Gateway::where('payment_id', 2)->get();
        $values = [];
        foreach ($gateways as $gateway) {
            if ($gateway->value) {
                $values[] = json_decode($gateway->value, true);
            }
        }

        return view('admin.gateways.cc', [
            'gateways' => $gateways,
            'values' => $values
        ]);
    }

}
