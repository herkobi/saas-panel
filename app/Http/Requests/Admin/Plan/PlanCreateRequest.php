<?php

namespace App\Http\Requests\Admin\Plan;

use App\Enums\PlanType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Yetkilendirme kontrolünü burada yapabilirsiniz
    }

    public function rules()
    {
        $rules = [
            'base' => ['nullable', 'exists:tenants,id'],
            'name' => ['required', 'string', Rule::unique('plans', 'name')],
            'desc' => ['nullable', 'string'],
            'free' => ['sometimes', 'boolean'],
            'grace_days' => ['integer', 'min:0'],
        ];

        if (!$this->boolean('free')) {
            $rules = array_merge($rules, [
                'periodicity_type' => ['required', 'string'],
                'periodicity' => ['required', 'integer', 'min:1'],
                'price' => ['required', 'numeric', 'min:0'],
                'currency_id' => ['required', 'exists:currencies,id'],
            ]);
        }

        foreach ($this->get('feature', []) as $featureId => $value) {
            $rules["limit_$featureId"] = ['sometimes', 'integer', 'min:0'];
        }

        return $rules;
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        $featureData = [];
        foreach ($this->get('feature', []) as $featureId => $value) {
            if ($value) {
                $featureData[$featureId] = [
                    'limit' => $this->input("feature.{$featureId}.limit"),
                ];
            }
        }

        $validated['features'] = $featureData;

        return $validated;
    }

    public function messages()
    {
        return [
            'base.exists' => 'Geçerli bir hesap seçiniz',
            'name.required' => 'Plan adı zorunludur.',
            'name.unique' => 'Bu plan adı zaten kullanılmaktadır.',
            'periodicity_type.required' => 'Plan döngüsü seçilmelidir.',
            'periodicity.required' => 'Döngü zamanı girilmelidir.',
            'price.required' => 'Plan ücreti girilmelidir.',
            'price.min' => 'Plan ücreti 0 veya daha büyük olmalıdır.',
            'currency_id.required' => 'Para birimi seçilmelidir.',
            'currency_id.exists' => 'Geçersiz para birimi.',
            'grace_days.integer' => 'Deneme süresi tam sayı olmalıdır.',
            'grace_days.min' => 'Deneme süresi 0 veya daha büyük olmalıdır.',
            'features.*.limit.required' => 'Kullanım limiti olan özellikler için limit değeri girilmelidir.',
            'features.*.limit.integer' => 'Limit değeri tam sayı olmalıdır.',
            'features.*.limit.min' => 'Limit değeri 0 veya daha büyük olmalıdır.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'grace_days' => $this->input('grace_days', 0),
        ]);
    }
}
