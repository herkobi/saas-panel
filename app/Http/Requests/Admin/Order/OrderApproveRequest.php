<?php

namespace App\Http\Requests\Admin\Order;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use App\Scopes\GlobalQuery;

class OrderApproveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order' => ['required', 'exists:orders,id'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'order' => $this->route('order')
        ]);
    }

    public function messages(): array
    {
        return [
            'order.required' => 'Sipariş bulunamadı.',
            'order.exists' => 'Sipariş bulunamadı.',
        ];
    }

    public function passedValidation(): void
    {
        $order = Order::withoutGlobalScope(GlobalQuery::class)
            ->with('orderstatus')
            ->findOrFail($this->order);

        if ($order->payment_type !== 'bank' ||
            !in_array($order->orderstatus->code, ['PENDING_PAYMENT', 'REVIEW'])) {
            abort(403, 'Geçersiz işlem.');
        }
    }
}
