<?php

namespace App\Http\Requests\Tenant\Space;

use App\Rules\UniqueTenant;
use Illuminate\Foundation\Http\FormRequest;

class SpaceCreateRequest extends FormRequest
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
                new UniqueTenant('spaces', 'name')
            ],
            'color' => ['required', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Alan adı alanı zorunludur.',
            'name.string' => 'Alan adı metin olmalıdır.',
            'name.max' => 'Alan adı en fazla :max karakter olabilir.',
            'name.unique_tenant' => 'Bu alan adı zaten kayıtlı.',

            'color.required' => 'Lütfen renk seçiniz',
            'color.string' => 'Lütfen geçerli bir renk seçiniz'
        ];
    }
}
