<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\PaymentCreateRequest;
use App\Http\Requests\Admin\Settings\PaymentUpdateRequest;
use App\Models\Admin\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
        $payment->status = $request->status;
        $payment->title = $request->title;
        $payment->desc = $request->desc;
        $payment->save();

        return Redirect::route('panel.settings.payments')->with('success', __('admin/settings/payments.update.success'));
    }

    public function create(): View
    {
        return view('admin.settings.payments.create');
    }

    public function store(PaymentCreateRequest $request) : RedirectResponse
    {
        $payment = Payment::create([
            'status' => $request->status,
            'title' => $request->title,
            'desc' => $request->desc,
        ]);

        return Redirect::route('panel.settings.payments')->with('success', __('/admin/settings/payments.store.success'));

    }

    public function destroy(Payment $payment): RedirectResponse
    {

        $gatewaysCount = $payment->gateways()->count();

        if($gatewaysCount == 0 && $payment->is_system != 1)
        {
            $payment->delete();
            return Redirect::route('panel.settings.payments')->with('success', __('admin/settings/payments.payment.delete.success.text'));
        }

        return Redirect::back()->with('error', __('admin/settings/payments.destroy.confirmation.error.text'));
    }
}
