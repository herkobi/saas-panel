<?php

namespace App\Http\Requests\Admin\Feature;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeatureCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('features', 'name')],
            'consumable' => ['nullable', 'boolean'],
            'periodicity_type' => ['nullable','required_if:consumable,true'],
            'periodicity' => ['nullable','required_if:consumable,true', 'numeric', 'min:1'],
            'quota' => ['nullable', 'boolean'],
            'postpaid' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Lütfen özellik adını giriniz',
            'name.string' => 'Lütfen geçerli bir özellik adı giriniz',
            'name.max' => 'Lütfen daha kısa bir özellik adı giriniz',
            'name.unique' => 'Girmiş olduğunuz isimde bir özellik bulunmaktadır. Lütfen farklı bir isim giriniz.',
            'periodicity_type.required_if' => 'Tüketilebilir özellikler için yenilenme sıklığı türünü seçmeniz gerekir.',
            'periodicity.required_if' => 'Tüketilebilir özellikler için yenilenme sıklığını belirtmelisiniz.',
        ];
    }
}
