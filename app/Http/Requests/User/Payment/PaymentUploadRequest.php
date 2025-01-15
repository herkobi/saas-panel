<?php

namespace App\Http\Requests\User\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'notes' => ['nullable', 'string', 'max:500']
        ];
    }

    public function messages(): array
    {
        return [
            'document.required' => 'Lütfen bir dekont yükleyin.',
            'document.file' => 'Yüklenen dosya geçerli değil.',
            'document.mimes' => 'Dekont PDF veya resim formatında olmalıdır.',
            'document.max' => 'Dekont boyutu en fazla 2MB olabilir.',
            'notes.max' => 'Açıklama en fazla 500 karakter olabilir.'
        ];
    }
}
