<?php

namespace App\Http\Requests\User\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceDetailUpdateRequest  extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'invoiceName' => ['required', 'string', 'max:255'],
            'taxOffice' => ['nullable', 'string', 'max:255'],
            'taxNumber' => ['required', 'string', 'max:255', Rule::unique('invoice_details', 'taxNumber')->ignore($this->route('detail'))],
            'mersis' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'state' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'zipCode' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string'],
            'email' => [
                'nullable',
                'string',
                'max:255',
                'email:rfc,dns'
            ],
            'kep' => [
                'nullable',
                'string',
                'max:255',
                'email:rfc,dns',
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
            'title.required' => 'Lütfen fatura bilgisi için bir başlık giriniz',
            'title.string' => 'Lütfen fatura bilgisi için geçerli bir başlık giriniz',
            'title.max' => 'Lütfen fatura bilgisi için daha kısa bir başlık giriniz',

            'invoiceName.required' => 'Lütfen firma adını giriniz',
            'invoiceName.string' => 'Lütfen geçerli bir firma adı giriniz',
            'invoiceName.max' => 'Lütfen daha kısa bir firma adı giriniz',

            'taxOffice.string' => 'Lütfen geçerli bir vergi dairesi giriniz',
            'taxOffice.max' => 'Lütfen daha kısa bir vergi dairesi giriniz',

            'taxNumber.required' => 'Lütfen vergi numarası giriniz',
            'taxNumber.string' => 'Lütfen geçerli bir vergi numarası giriniz',
            'taxNumber.max' => 'Lütfen daha kısa bir vergi numarası giriniz',
            'taxNumber.unique' => 'Lütfen daha kısa bir vergi numarası giriniz',

            'address.required' => 'Lütfen şirket adresini giriniz',
            'address.string' => 'Lütfen geçerli bir şirket adresi giriniz',

            'zipCode.string' => 'Lütfen geçerli bir ilçe adı giriniz',
            'zipCode.max' => 'Lütfen daha kısa bir ilçe adı giriniz',

            'state.required' => 'Lütfen ilçe adını giriniz',
            'state.string' => 'Lütfen geçerli bir ilçe adı giriniz',
            'state.max' => 'Lütfen daha kısa bir ilçe adı giriniz',

            'city.required' => 'Lütfen şehir seçiniz',
            'city.string' => 'Lütfen geçerli bir şehir giriniz',
            'city.max' => 'Lütfen daha kısa bir şehir adı giriniz',

            'country.required' => 'Lütfen ülke adını giriniz',
            'country.string' => 'Lütfen geçerli bir ülke adı giriniz',
            'country.max' => 'Lütfen daha kısa bir ülke adı giriniz',

            'mersis_no.string' => 'Lütfen geçerli bir Mersis numarası giriniz',
            'mersis_no.max' => 'Lütfen daha kısa bir Mersis numarası giriniz',

            'phone.string' => 'Lütfen geçerli bir telefon numarası giriniz',

            'email.email' => 'Lütfen geçerli bir e-posta adresi giriniz',
            'email.string' => 'Lütfen geçerli bir e-posta adresi giriniz',
            'email.max' => 'Lütfen daha kısa bir e-posta adresi giriniz',

            'kep.email' => 'Lütfen geçerli bir Kep e-posta adresi giriniz',
            'kep.string' => 'Lütfen geçerli bir Kep e-posta adresi giriniz',
            'kep.max' => 'Lütfen daha kısa bir Kep e-posta adresi giriniz',
        ];
    }
}
