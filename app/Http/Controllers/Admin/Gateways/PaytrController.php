<?php

namespace App\Http\Controllers\Admin\Gateways;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Gateways\Paytr\PaytrUpdateRequest;
use App\Models\Admin\Currency;
use App\Models\Admin\Gateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PaytrController extends Controller
{

    const PAYMENT_ID = 2;
    const CODE = 'paytr';

    public function edit(Gateway $paytr): View
    {
        $values = json_decode($paytr->value, true);
        $currencies = Currency::pluck('title', 'id')->toArray();

        return view('admin.gateways.paytr.edit', [
            'paytr' => $paytr,
            'values' => $values,
            'currencies' => $currencies
        ]);
    }

    public function update(PaytrUpdateRequest $request, Gateway $paytr): RedirectResponse
    {

        $paytr->update([
            'status' => $request->status,
            'title' => $request->title,
            'desc' => $request->desc,
            'currency_id' => $request->currency_id,
            'payment_id' => self::PAYMENT_ID,
            'value' => json_encode([
                'code' => self::CODE,
                'logo' => $request->logo,
                'merchant_id' => $request->merchant_id,
                'merchant_key' => $request->merchant_key,
                'merchant_salt' => $request->merchant_salt,
                'merchant_ok_url' => $request->merchant_ok_url,
                'merchant_fail_url' => $request->merchant_fail_url,
            ]),
        ]);

        return Redirect::route('panel.gateways.cc')->with('success', __('admin/gateways/cc.update.success'));

    }
}
