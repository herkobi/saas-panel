<?php

namespace App\Http\Requests\Admin\Plan;

use Illuminate\Foundation\Http\FormRequest;

class PlanCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_featured' => ['boolean'],
            'is_free' => ['boolean'],
            'billing_period' => ['required', 'in:monthly,yearly,both'],
            'country_code' => ['required_if:is_free,false', 'nullable', 'string', 'size:2'],
            'currency_code' => ['required_if:is_free,false', 'nullable', 'string', 'size:3'],
            'tax_rate_code' => ['required_if:is_free,false', 'nullable', 'string'],
            'monthly_price' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    // Ücretsiz olmayan bir planda ve aylık veya her ikisi seçildiyse zorunlu
                    if ($this->is_free == false &&
                        in_array($this->billing_period, ['monthly', 'both']) &&
                        ($value === null || $value === '')) {
                        $fail('Ücretli planlar için aylık fiyat zorunludur.');
                    }
                },
            ],
            'yearly_price' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    // Ücretsiz olmayan bir planda ve yıllık veya her ikisi seçildiyse zorunlu
                    if ($this->is_free == false &&
                        in_array($this->billing_period, ['yearly', 'both']) &&
                        ($value === null || $value === '')) {
                        $fail('Ücretli planlar için yıllık fiyat zorunludur.');
                    }
                },
            ],
            'trial_days' => ['nullable', 'integer', 'min:0'],
            'grace_period_days' => ['nullable', 'integer', 'min:0'],
            'payment_timing' => ['required_if:is_free,false', 'nullable', 'in:upfront,later'],
            'status' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'planFeatures' => ['nullable', 'array'],
            'planFeatures.*.feature_id' => ['required', 'exists:features,id'],
            'planFeatures.*.access_type' => ['required', 'in:access_only,limited'],
            'planFeatures.*.limit_type' => ['required_if:planFeatures.*.access_type,limited', 'nullable', 'in:renewable,cumulative'],
            'planFeatures.*.limit_value' => [
                'required_if:planFeatures.*.access_type,limited',
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $limitType = $this->input("planFeatures.{$index}.limit_type");

                    if ($limitType === 'cumulative' && $value < 0) {
                        $fail('Sabit limit için negatif değer girilemez.');
                    }
                },
            ],
            'planFeatures.*.limit_reset_period' => ['required_if:planFeatures.*.limit_type,renewable', 'nullable', 'in:hourly,daily,weekly,monthly,yearly'],
            'planFeatures.*.restore_on_delete' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Plan adı zorunludur.',
            'billing_period.required' => 'Faturalama periyodu zorunludur.',
            'billing_period.in' => 'Geçersiz faturalama periyodu.',
            'country_code.required_if' => 'Ücretli planlar için ülke kodu zorunludur.',
            'currency_code.required_if' => 'Ücretli planlar için para birimi kodu zorunludur.',
            'tax_rate_code.required_if' => 'Ücretli planlar için vergi oranı kodu zorunludur.',
            'payment_timing.required_if' => 'Ücretli planlar için ödeme zamanlaması zorunludur.',
            'payment_timing.in' => 'Geçersiz ödeme zamanlaması.',
            'planFeatures.*.feature_id.required' => 'Özellik ID zorunludur.',
            'planFeatures.*.feature_id.exists' => 'Geçersiz özellik ID.',
            'planFeatures.*.access_type.required' => 'Erişim tipi zorunludur.',
            'planFeatures.*.access_type.in' => 'Geçersiz erişim tipi.',
            'planFeatures.*.limit_type.required_if' => 'Limitli erişim için limit tipi zorunludur.',
            'planFeatures.*.limit_type.in' => 'Geçersiz limit tipi.',
            'planFeatures.*.limit_value.required_if' => 'Limitli erişim için limit değeri zorunludur.',
            'planFeatures.*.limit_reset_period.required_if' => 'Yenilenebilir limitler için yenileme periyodu zorunludur.',
            'planFeatures.*.limit_reset_period.in' => 'Geçersiz yenileme periyodu.',
        ];
    }
}
