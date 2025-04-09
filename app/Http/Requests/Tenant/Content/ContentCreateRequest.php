<?php

namespace App\Http\Requests\Tenant\Content;

use App\Rules\UniqueTenant;
use Illuminate\Foundation\Http\FormRequest;

class ContentCreateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                new UniqueTenant('content', 'name')
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Alan adı alanı zorunludur.',
            'name.string' => 'Alan adı metin olmalıdır.',
            'name.max' => 'Alan adı en fazla :max karakter olabilir.',
            'name.unique_tenant' => 'Bu alan adı zaten kayıtlı.'
        ];
    }
}
