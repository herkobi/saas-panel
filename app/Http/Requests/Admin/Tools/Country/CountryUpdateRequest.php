<?php

namespace App\Http\Requests\Admin\Tools\Country;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Status;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CountryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(Status::class)],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('countries')->where(function ($query) {
                    return $query->where('code', $this->code)
                               ->where('phone', $this->phone);
                })->ignore($this->country, 'id')
            ],
            'code' => [
                'required',
                'string',
                'alpha',
                'uppercase',
                Rule::unique('countries')->where(function ($query) {
                    return $query->where('name', $this->name)
                               ->where('phone', $this->phone);
                })->ignore($this->country, 'id')
            ],
            'phone' => [
                'required',
                'integer',
                Rule::unique('countries')->where(function ($query) {
                    return $query->where('name', $this->name)
                               ->where('code', $this->code);
                })->ignore($this->country, 'id')
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Durum alanı zorunludur.',
            'status.integer' => 'Durum alanı sayısal olmalıdır.',
            'status.in' => 'Durum geçersiz.',

            'name.required' => 'Ülke adı alanı zorunludur.',
            'name.string' => 'Ülke adı metin olmalıdır.',
            'name.max' => 'Ülke adı en fazla :max karakter olabilir.',
            'name.unique' => 'Bu ülke adı zaten kayıtlı.',

            'code.required' => 'Ülke kodu alanı zorunludur.',
            'code.string' => 'Ülke kodu metin olmalıdır.',
            'code.unique' => 'Bu ülke kodu zaten kayıtlı.',
            'code.alpha' => 'Ülke kodu sadece harflerden oluşmalıdır.',
            'code.uppercase' => 'Ülke kodu büyük harflerden oluşmalıdır.',

            'phone.required' => 'Ülke telefon kodu adı alanı zorunludur.',
            'phone.integer' => 'Ülke telefon kodu rakam olmalıdır.',
            'phone.unique' => 'Bu telefon kodu zaten kayıtlı.',
        ];
    }
}
