<?php

namespace App\Http\Requests\Tenant\Pixel;

use App\Enums\PixelType;
use App\Rules\UniqueTenant;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class PixelUpdateRequest extends FormRequest
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
                (new UniqueTenant('pixels', 'name'))->ignore($this->route('pixel')->id)
            ],
            'type' => ['required', new Enum(PixelType::class)],
            'value' => ['required', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Alan adı alanı zorunludur.',
            'name.string' => 'Alan adı metin olmalıdır.',
            'name.max' => 'Alan adı en fazla :max karakter olabilir.',
            'name.unique_tenant' => 'Bu alan adı zaten kayıtlı.',

            'type.required' => 'Lütfen pixel türü seçiniz',
            'type.enum' => 'Lütfen geçerli bir pixel türü seçiniz',

            'value.required' => 'Lütfen pixel değerini giriniz',
            'value.string' => 'Lütfen geçerli bir pixel değeri giriniz'
        ];
    }
}
