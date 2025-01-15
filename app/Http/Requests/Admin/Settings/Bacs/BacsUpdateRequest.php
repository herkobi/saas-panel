<?php

namespace App\Http\Requests\Admin\Settings\Bacs;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Status;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class BacsUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(Status::class)],
            'logo' => ['nullable', 'image', 'max:2048'],
            'bank_name' => ['required', 'string', 'max:255'],
            'account_holder' => ['required', 'string', 'max:255'],
            'account_number' => [
                'nullable',
                'string',
                'required_without:iban',
                Rule::unique('bacs', 'account_number')->ignore($this->bacs) // Update requestte
            ],
            'iban' => [
                'nullable',
                'string',
                'required_without:account_number',
                Rule::unique('bacs', 'iban')->ignore($this->bacs, 'id') // Update requestte
            ],
            'swift' => ['nullable', 'string'],
            'currency_id' => ['required', 'uuid', 'exists:currencies,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Durum alanı zorunludur.',
            'status.enum' => 'Geçersiz durum değeri.',

            'logo.image' => 'Logo bir resim dosyası olmalıdır.',
            'logo.max' => 'Logo boyutu en fazla 2MB olabilir.',

            'bank_name.required' => 'Banka adı zorunludur.',
            'bank_name.string' => 'Banka adı metin formatında olmalıdır.',
            'bank_name.max' => 'Banka adı en fazla 255 karakter olabilir.',

            'account_holder.required' => 'Hesap sahibi zorunludur.',
            'account_holder.string' => 'Hesap sahibi metin formatında olmalıdır.',
            'account_holder.max' => 'Hesap sahibi en fazla 255 karakter olabilir.',

            'account_number.required_without' => 'IBAN girilmediyse hesap numarası zorunludur.',
            'account_number.unique' => 'Bu hesap numarası ile kayıtlı hesap bulunmaktadır.',

            'iban.required_without' => 'Hesap numarası girilmediyse IBAN zorunludur.',
            'iban.unique' => 'Bu IBAN adresi ile kayıtlı hesap bulunmaktadır.',

            'swift.string' => 'SWIFT kodu metin formatında olmalıdır.',

            'currency_id.required' => 'En az bir para birimi seçilmelidir.',
            'currency_id.uuid' => 'Para birimi seçimi geçersiz formatta.',
            'currency_id.exists' => 'Seçilen para birimi geçersiz.',
        ];
    }
}
