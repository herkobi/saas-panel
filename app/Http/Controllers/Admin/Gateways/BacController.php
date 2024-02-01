<?php

namespace App\Http\Controllers\Admin\Gateways;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Gateways\Bac\BacCreateRequest;
use App\Http\Requests\Admin\Gateways\Bac\BacUpdateRequest;
use App\Models\Admin\Currency;
use App\Models\Admin\Gateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BacController extends Controller
{

    const PAYMENT_ID = 1;

    public function create(): View
    {
        $currencies = Currency::pluck('title', 'id')->toArray();
        return view('admin.gateways.bac.create', [
            'currencies' => $currencies
        ]);

    }

    public function store(BacCreateRequest $request): RedirectResponse
    {
        Gateway::create([
            'status' => $request->status,
            'payment_id' => self::PAYMENT_ID,
            'currency_id' => $request->currency_id,
            'title' => $request->title,
            'desc' => $request->desc,
            'value' => json_encode([
                'account_name' => $request->account_name,
                'account_bank' => $request->account_bank,
                'account_number' => $request->account_number,
                'account_iban' => $request->account_iban,
                'account_swift' => $request->account_swift,
            ]),
        ]);

        return Redirect::route('panel.gateways.bac')->with('success', __('admin/gateways/bac.store.success'));
    }

    public function edit(Gateway $bac): View
    {
        $values = json_decode($bac->value, true);
        $currencies = Currency::pluck('title', 'id')->toArray();

        return view('admin.gateways.bac.edit', [
            'bac' => $bac,
            'values' => $values,
            'currencies' => $currencies
        ]);
    }

    public function update(BacUpdateRequest $request, Gateway $bac): RedirectResponse
    {

        $bac->update([
            'status' => $request->status,
            'title' => $request->title,
            'desc' => $request->desc,
            'currency_id' => $request->currency_id,
            'payment_id' => self::PAYMENT_ID,
            'value' => json_encode([
                'account_name' => $request->account_name,
                'account_bank' => $request->account_bank,
                'account_number' => $request->account_number,
                'account_iban' => $request->account_iban,
                'account_swift' => $request->account_swift,
            ]),
        ]);

        return Redirect::route('panel.gateways.bac')->with('success', __('admin/gateways/bac.update.success'));

    }

    public function destroy(Gateway $bac): RedirectResponse
    {
        if($bac->delete()) {
            return Redirect::route('panel.gateways.bac')->with('success', __('admin/gateways/bac.destroy.success'));
        }

        return Redirect::route('panel.gateways.bac')->with('error', __('admin/gateways/bac.destroy.error'));
    }
}
