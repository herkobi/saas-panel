<?php

namespace App\Http\Requests\Admin\Settings\Agreement;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Status;
use App\Enums\UserType;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class AgreementUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(Status::class)],
            'show_on_register' => ['required', 'boolean'],
            'show_on_payment' => ['required', 'boolean'],
            'title' => ['required', 'string', 'max:100', Rule::unique('agreements', 'title')->ignore($this->agreement, 'id')],
            'description' => ['nullable', 'string', 'max:255'],
            'user_type' => ['required', new Enum(UserType::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Durum seçiniz.',

            'show_on_register.required' => 'Üyelik formunda gösterim seçimi zorunludur.',
            'show_on_register.boolean' => 'Üyelik formunda gösterim değeri geçersiz.',

            'show_on_payment.required' => 'Ödeme formunda gösterim seçimi zorunludur.',
            'show_on_payment.boolean' => 'Ödeme formunda gösterim değeri geçersiz.',

            'title.required' => 'Sözleşme adı zorunludur.',
            'title.max' => 'Sözleşme adı en fazla :max karakter olabilir.',
            'title.unique' => 'Bu sözleşme adı daha önce kullanılmış.',

            'description.max' => 'Sözleşme açıklaması en fazla :max karakter olabilir.',
        ];
    }
}
