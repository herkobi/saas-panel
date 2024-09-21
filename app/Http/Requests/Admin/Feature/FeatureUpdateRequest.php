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

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('features', 'title')->ignore($this->feature, 'id')],
            'desc' => ['required', 'string', 'max:255'],
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
            'title.required' => 'Lütfen özellik adını giriniz',
            'title.string' => 'Lütfen geçerli bir özellik adı giriniz',
            'title.max' => 'Lütfen daha kısa bir özellik adı giriniz',
            'title.unique' => 'Girmiş olduğunuz isimde bir özellik bulunmaktadır. Lütfen farklı bir isim giriniz.',
            'desc.required' => 'Lütfen açıklama giriniz',
            'desc.string' => 'Lütfen geçerli bir açıklama giriniz',
            'desc.max' => 'Lütfen daha kısa bir açıklama giriniz.'
        ];
    }
}
