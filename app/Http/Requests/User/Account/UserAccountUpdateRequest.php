<?php

namespace App\Http\Requests\User\Account;

use App\Traits\AuthUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserAccountUpdateRequest extends FormRequest
{

    use AuthUser;

    public function __construct() {
        $this->initializeAuthUser();
    }

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
            'invoice_name' => ['required', 'string', 'max:255'],
            'tax_office' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['required', 'string', 'max:255', Rule::unique('accounts', 'tax_number')->ignore($this->user->id, 'user_id')],
            'mersis' => ['nullable', 'string', 'max:255', Rule::unique('accounts', 'mersis')->ignore($this->user->id, 'user_id')],
            'address' => ['required', 'string'],
            'district' => ['required', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:255'],
            'country_id' => ['required', 'string', 'exists:countries,id'],
            'state_id' => ['required', 'string', 'exists:states,id'],
            'phone' => ['nullable', 'string', Rule::unique('accounts', 'phone')->ignore($this->user->id, 'user_id')],
            'gsm' => ['nullable', 'string', Rule::unique('accounts', 'gsm')->ignore($this->user->id, 'user_id')],
            'email' => [
                'nullable',
                'max:255',
                'email:rfc,dns',
                Rule::unique('accounts', 'email')->ignore($this->user->id, 'user_id')
            ],
            'kep' => [
                'nullable',
                'max:255',
                'email:rfc,dns',
                Rule::unique('accounts', 'kep')->ignore($this->user->id, 'user_id')
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'invoice_name.required' => 'Lütfen firma adını giriniz',
            'invoice_name.string' => 'Lütfen geçerli bir firma adı giriniz',
            'invoice_name.max' => 'Lütfen daha kısa bir firma adı giriniz',

            'tax_office.string' => 'Lütfen geçerli bir vergi dairesi giriniz',
            'tax_office.max' => 'Lütfen daha kısa bir vergi dairesi giriniz',

            'tax_number.required' => 'Lütfen vergi numarası giriniz',
            'tax_number.string' => 'Lütfen geçerli bir vergi numarası giriniz',
            'tax_number.max' => 'Lütfen daha kısa bir vergi numarası giriniz',

            'address.required' => 'Lütfen şirket adresini giriniz',
            'address.string' => 'Lütfen geçerli bir şirket adresi giriniz',

            'zip_code.string' => 'Lütfen geçerli bir ilçe adı giriniz',
            'zip_code.max' => 'Lütfen daha kısa bir ilçe adı giriniz',

            'state_id.required' => 'Lütfen ilçe adını giriniz',
            'state_id.string' => 'Lütfen geçerli bir ilçe adı giriniz',
            'state_id.exists' => 'Lütfen geçerli bir ilçe adı giriniz',

            'district.required' => 'Lütfen şehir seçiniz',
            'district.string' => 'Lütfen geçerli bir şehir giriniz',
            'district.max' => 'Lütfen daha kısa bir şehir adı giriniz',

            'country_id.required' => 'Lütfen ülke adını giriniz',
            'country_id.string' => 'Lütfen geçerli bir ülke adı giriniz',
            'country_id.exists' => 'Lütfen geçerli bir ülke adı giriniz',

            'mersis.string' => 'Lütfen geçerli bir Mersis numarası giriniz',
            'mersis.max' => 'Lütfen daha kısa bir Mersis numarası giriniz',

            'phone.string' => 'Lütfen geçerli bir telefon numarası giriniz',
            'gsm.string' => 'Lütfen geçerli bir telefon numarası giriniz',

            'email.email' => 'Lütfen geçerli bir e-posta adresi giriniz',
            'email.max' => 'Lütfen daha kısa bir e-posta adresi giriniz',

            'kep.email' => 'Lütfen geçerli bir Kep e-posta adresi giriniz',
            'kep.max' => 'Lütfen daha kısa bir Kep e-posta adresi giriniz',
        ];
    }
}
