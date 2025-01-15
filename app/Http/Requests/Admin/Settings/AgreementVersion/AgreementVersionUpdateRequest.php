<?php

namespace App\Http\Requests\Admin\Settings\AgreementVersion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgreementVersionUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'version' => ['required', 'string', 'max:100', Rule::unique('agreement_versions', 'version')->where('agreement_id', $this->route('agreement_id'))->ignore($this->route('version_id'))],
            'content' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'version.required' => 'Versiyon numarası gereklidir.',
            'version.string' => 'Versiyon numarası metin formatında olmalıdır.',
            'version.max' => 'Versiyon numarası çok uzun.',
            'version.unique' => 'Bu versiyon numarası daha önce kullanılmış.',
            'content.required' => 'Sözleşme içeriği gereklidir.',
            'content.string' => 'Sözleşme içeriği metin formatında olmalıdır.',
        ];
    }
}
