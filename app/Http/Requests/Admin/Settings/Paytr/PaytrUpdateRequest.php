<?php

namespace App\Http\Requests\Admin\Settings\Paytr;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PaytrUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'integer', new Enum(Status::class)],
            'account_name' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'uuid', 'exists:currencies,id'],
            'merchant_id' => ['required',],
            'merchant_key' => ['required'],
            'merchant_salt' => ['required'],
            'success_url' => ['required', 'url'],
            'fail_url' => ['required', 'url'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status alanı zorunludur.',
            'status.integer' => 'Status alanı sayısal bir değer olmalıdır.',
            'status.enum' => 'Geçersiz status değeri.',

            'account_name.required' => 'Başlık alanı zorunludur.',
            'account_name.string' => 'Başlık metin formatında olmalıdır.',
            'account_name.max' => 'Başlık en fazla 255 karakter olabilir.',

            'currency_id.required' => 'Para birimi alanı zorunludur.',
            'currency_id.uuid' => 'Geçersiz para birimi formatı.',
            'currency_id.exists' => 'Seçilen para birimi bulunamadı.',

            'merchant_id.required' => 'Merchant ID alanı zorunludur.',
            'merchant_key.required' => 'Merchant Key alanı zorunludur.',
            'merchant_salt.required' => 'Merchant Salt alanı zorunludur.',

            'success_url.required' => 'Başarılı yönlendirme adresi zorunludur.',
            'success_url.url' => 'Geçerli bir başarılı yönlendirme adresi giriniz.',

            'fail_url.required' => 'Başarısız yönlendirme adresi zorunludur.',
            'fail_url.url' => 'Geçerli bir başarısız yönlendirme adresi giriniz.',
        ];
    }
}
