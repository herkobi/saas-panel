<?php

namespace App\Http\Requests\Admin\Tools\State;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Status;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StateUpdateRequest extends FormRequest
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
                Rule::unique('states')->where(function ($query) {
                    return $query->where('code', $this->code)
                               ->where('phone', $this->phone);
                })->ignore($this->state, 'id')
            ],
            'code' => [
                'required',
                'integer',
                Rule::unique('states')->where(function ($query) {
                    return $query->where('name', $this->name)
                               ->where('phone', $this->phone);
                })->ignore($this->state, 'id')
            ],
            'phone' => [
                'required',
                'integer',
                Rule::unique('states')->where(function ($query) {
                    return $query->where('name', $this->name)
                               ->where('code', $this->code);
                })->ignore($this->state, 'id')
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Durum alanı zorunludur.',
            'status.integer' => 'Durum alanı sayısal olmalıdır.',
            'status.in' => 'Durum geçersiz.',

            'name.required' => 'Eyalet/Şehir adı alanı zorunludur.',
            'name.string' => 'Eyalet/Şehir adı metin olmalıdır.',
            'name.max' => 'Eyalet/Şehir adı en fazla :max karakter olabilir.',
            'name.unique' => 'Bu eyalet/şehir adı zaten kayıtlı.',

            'code.required' => 'Plaka kodu alanı zorunludur.',
            'code.integer' => 'Plaka kodu rakam olmalıdır.',
            'code.unique' => 'Bu plaka kodu zaten kayıtlı.',

            'phone.required' => 'Telefon kodu adı alanı zorunludur.',
            'phone.integer' => 'Telefon kodu rakam olmalıdır.',
            'phone.unique' => 'Telefon kodu zaten kayıtlı.',
        ];
    }
}
