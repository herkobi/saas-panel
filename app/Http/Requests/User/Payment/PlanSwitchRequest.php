<?php

namespace App\Http\Requests\User\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PlanSwitchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plan_id' => ['required', 'exists:plans,id'],
            'payment_method' => ['required', 'in:bank,credit_card'],
            'invoice_name' => ['required', 'string', 'max:255'],
            'tax_number' => ['required_if:tax_office,null', 'string', 'max:20'],
            'tax_office' => ['required_if:tax_number,null', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'zip_code' => ['required', 'string', 'max:10'],
            'district' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'exists:countries,id'],
            'state_id' => ['required', 'exists:states,id'],
            'agreements' => ['required', 'array'],
            'agreements.*' => ['required', 'boolean', 'accepted']
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_name.required' => 'Fatura adı zorunludur',
            'tax_number.required_if' => 'Vergi/TC kimlik numarası zorunludur',
            'address.required' => 'Adres zorunludur',
            'country_id.required' => 'Ülke seçimi zorunludur',
            'state_id.required' => 'Şehir seçimi zorunludur',
            'agreements.*.accepted' => 'Sözleşmeleri kabul etmeniz gerekmektedir'
        ];
    }
}
