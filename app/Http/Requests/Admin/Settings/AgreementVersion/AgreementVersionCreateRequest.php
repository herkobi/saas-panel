<?php

namespace App\Http\Requests\Admin\Settings\AgreementVersion;

use App\Models\Agreement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgreementVersionCreateRequest extends FormRequest
{

    protected ?Agreement $agreement = null;

    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->agreement = Agreement::where('slug', $this->route('agreement'))->firstOrFail();
    }

    public function rules(): array
    {
        $agreement = Agreement::where('slug', $this->route('agreement'))->first();

        return [
            'version' => ['required', 'string', 'max:100', Rule::unique('agreement_versions', 'version') ->where('agreement_id', $this->agreement?->id)],
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
