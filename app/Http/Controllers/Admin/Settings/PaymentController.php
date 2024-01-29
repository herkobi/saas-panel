<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Payment\PaymentUpdateRequest;
use App\Http\Requests\Admin\Settings\Payment\PaymentCreateRequest;
use App\Models\Admin\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class PaymentController extends Controller
{

    public function index(): View
    {
        $payments = Payment::all();
        return view('admin.settings.payments.index', [
            'payments' => $payments
        ]);
    }

    public function edit(Payment $payment): View
    {
        return view('admin.settings.payments.edit', [
            'payment' => $payment
        ]);
    }

    public function update(PaymentUpdateRequest $request, Payment $payment): RedirectResponse
    {
        if(Route::has('panel.gateways.'. Str::slug($request->title, '-')))
        {
            $payment->status = $request->status;
            $payment->title = $request->title;
            $payment->code = Str::slug($request->title, '-');
            $payment->desc = $request->desc;
            $payment->save();

            return Redirect::route('panel.settings.payments')->with('success', __('admin/settings/payments.update.success'));
        }

        return Redirect::route('panel.settings.payments')->with('error', __('admin/settings/payments.nofile.error'));

    }

    public function create(): View
    {
        return view('admin.settings.payments.create');
    }

    public function store(PaymentCreateRequest $request) : RedirectResponse
    {
        if(Route::has('panel.gateways.'. Str::slug($request->title, '-')))
        {
            $payment = Payment::create([
                'status' => $request->status,
                'title' => $request->title,
                'code' => Str::slug($request->title, '-'),
                'desc' => $request->desc,
            ]);

            return Redirect::route('panel.settings.payments')->with('success', __('/admin/settings/payments.store.success'));
        }

        return Redirect::route('panel.settings.payments')->with('error', __('admin/settings/payments.nofile.error'));
    }

    public function destroy(Payment $payment): RedirectResponse
    {

        $gatewaysCount = $payment->gateways()->count();

        if($gatewaysCount == 0 && $payment->is_system != 1)
        {
            $payment->delete();
            return Redirect::route('panel.settings.payments')->with('success', __('admin/settings/payments.destroy.success'));
        }

        return Redirect::back()->with('error', __('admin/settings/payments.destroy.error'));
    }
}
