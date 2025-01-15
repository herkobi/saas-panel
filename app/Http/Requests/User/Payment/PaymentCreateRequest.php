<?php

namespace App\Http\Requests\User\Payment;

use App\Models\Agreement;
use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $this->getPaymentAgreements();

        $plan = Plan::find($this->input('plan_id'));
        $paymentMethodRule = $plan && $plan->price > 0
            ? ['required', 'string', 'in:bank,credit_card']
            : ['nullable', 'string'];

        return [
            'plan_id' => ['required', 'exists:plans,id'],
            'payment_method' => $paymentMethodRule,
            'invoice_name' => ['required', 'string', 'max:255'],
            'tax_number' => ['required', 'string', 'max:255'],
            'tax_office' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'zip_code' => ['required', 'string', 'max:20'],
            'district' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'exists:countries,id'],
            'state_id' => [
                'nullable',
                'exists:states,id',
                Rule::requiredIf(fn() => $this->hasStates())
            ],
            'notes' => ['nullable', 'string'],
            'agreements' => ['required', 'array']
        ];
    }

    protected function prepareForValidation(): void
    {
        if($this->has('plan_id')) {
            $plan = Plan::find($this->input('plan_id'));
            if($plan) {
                $this->merge([
                    'currency_id' => $plan->currency_id,
                    'amount' => $plan->price
                ]);
            }
        }
    }

    protected function getPaymentAgreements()
    {
        $agreements = Agreement::where('show_on_payment', true)
            ->whereHas('versions', function($query) {
                $query->where('require_acceptance', true)
                    ->where('block_access', true);
            })
            ->pluck('id')
            ->toArray();

        foreach ($agreements as $agreementId) {
            $this->rules["agreements.{$agreementId}"] = ['required', 'accepted'];
        }
    }

    protected function hasStates(): bool
    {
        return $this->country_id && \App\Models\State::where('country_id', $this->country_id)->exists();
    }

    public function messages(): array
    {
        return [
            'plan_id' => 'Lütfen geçerli bir plan seçiniz',
            'payment_method.required' => 'Lütfen bir ödeme yöntemi seçiniz.',
            'payment_method.in' => 'Geçersiz ödeme yöntemi.',
            'invoice_name.required' => 'Fatura adı zorunludur.',
            'tax_number.required' => 'Vergi/TC kimlik numarası zorunludur.',
            'address.required' => 'Adres zorunludur.',
            'zip_code.required' => 'Posta kodu zorunludur.',
            'district.required' => 'İlçe zorunludur.',
            'country_id.required' => 'Ülke seçimi zorunludur.',
            'country_id.exists' => 'Geçersiz ülke seçimi.',
            'state_id.required' => 'Seçilen ülke için şehir/eyalet seçimi zorunludur.',
            'state_id.exists' => 'Geçersiz şehir/eyalet seçimi.',
            'agreements.required' => 'Bu sözleşmeyi kabul etmelisiniz.',
        ];
    }
}
