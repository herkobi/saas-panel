<?php

namespace App\Http\Requests\Admin\Feature;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeatureUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'consumable' => $this->boolean('consumable'),
            'quota' => $this->boolean('quota'),
            'postpaid' => $this->boolean('postpaid'),
            'periodicity_type' => $this->boolean('has_periodic') ? $this->periodicity_type : null,
            'periodicity' => $this->boolean('has_periodic') ? $this->periodicity : null
        ]);
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'consumable' => ['sometimes', 'boolean'],
            'quota' => ['sometimes', 'boolean'],
            'postpaid' => ['sometimes', 'boolean']
        ];

        if ($this->boolean('consumable') && $this->boolean('has_periodic')) {
            $rules['periodicity_type'] = ['required', 'string'];
            $rules['periodicity'] = ['required', 'integer', 'min:1'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Lütfen özellik adını giriniz',
            'name.string' => 'Lütfen geçerli bir özellik adı giriniz',
            'name.max' => 'Lütfen daha kısa bir özellik adı giriniz',
            'periodicity_type.required' => 'Periyodik yenileme için periyot türü seçmelisiniz',
            'periodicity.required' => 'Periyodik yenileme için periyot değeri girmelisiniz',
            'periodicity.min' => 'Periyot değeri en az 1 olmalıdır',
        ];
    }
}
