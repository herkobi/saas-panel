<?php

namespace App\Http\Requests\Tenant\Subscription\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    /**
     * Belirle kullanıcının bu isteği yapmaya yetkisi var mı
     */
    public function authorize(): bool
    {
        // Kullanıcının tenant_owner olup olmadığını kontrol et
        return $this->user() && $this->user()->isTenantOwner();
    }

    /**
     * İstek için doğrulama kurallarını al
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['sometimes', 'boolean'],
            'send_invitation' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Doğrulama mesajlarını özelleştir
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'İsim alanı zorunludur.',
            'name.max' => 'İsim en fazla 255 karakter olabilir.',
            'email.required' => 'E-posta alanı zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi girin.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
            'password.required' => 'Şifre alanı zorunludur.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
        ];
    }
}
