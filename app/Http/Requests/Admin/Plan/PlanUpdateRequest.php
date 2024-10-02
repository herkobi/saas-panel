<?php

namespace App\Http\Requests\Admin\Plan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Yetkilendirme kontrolünü burada yapabilirsiniz
    }

    public function rules()
    {
        $planId = $this->route('plan'); // URL'den plan ID'sini al

        $rules = [
            'title' => ['required', 'string', Rule::unique('plans', 'title')->ignore($planId)],
            'desc' => ['nullable', 'string'],
            'free' => ['sometimes', 'boolean'],
        ];

        if (!$this->boolean('free')) {
            $rules = array_merge($rules, [
                'periodicity_type' => ['required', 'string'],
                'periodicity' => ['required', 'integer', 'min:1'],
                'price' => ['required', 'numeric', 'min:0'],
            ]);
        }

        $rules['grace_days'] = ['integer', 'min:0'];

        // Feature'lar için dinamik kurallar
        $features = $this->input('features', []);
        foreach ($features as $featureId => $featureData) {
            if (isset($featureData['selected']) && $featureData['selected']) {
                // Eğer özellik tüketilebilir (consumable) ise, limit gereklidir
                if (isset($featureData['consumable']) && $featureData['consumable']) {
                    $rules["features.{$featureId}.limit"] = ['required', 'integer', 'min:0'];
                }

                // Eğer özellik kota içeriyorsa, quota gereklidir
                if (isset($featureData['quota']) && $featureData['quota']) {
                    $rules["features.{$featureId}.quota"] = ['required', 'integer', 'min:0'];
                }
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'Plan adı zorunludur.',
            'title.unique' => 'Bu plan adı zaten kullanılmaktadır.',
            'periodicity_type.required' => 'Plan döngüsü seçilmelidir.',
            'periodicity.required' => 'Döngü zamanı girilmelidir.',
            'price.required' => 'Plan ücreti girilmelidir.',
            'price.min' => 'Plan ücreti 0 veya daha büyük olmalıdır.',
            'grace_days.integer' => 'Deneme süresi tam sayı olmalıdır.',
            'grace_days.min' => 'Deneme süresi 0 veya daha büyük olmalıdır.',
            'features.*.limit.required' => 'Kullanım limiti olan özellikler için limit değeri girilmelidir.',
            'features.*.quota.required' => 'Kota olan özellikler için kota değeri girilmelidir.',
            'features.*.limit.integer' => 'Limit değeri tam sayı olmalıdır.',
            'features.*.quota.integer' => 'Kota değeri tam sayı olmalıdır.',
            'features.*.limit.min' => 'Limit değeri 0 veya daha büyük olmalıdır.',
            'features.*.quota.min' => 'Kota değeri 0 veya daha büyük olmalıdır.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'grace_days' => $this->input('grace_days', 0),
        ]);
    }
}
