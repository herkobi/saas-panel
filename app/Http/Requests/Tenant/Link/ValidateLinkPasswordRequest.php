<?php

namespace App\Http\Requests\Tenant\Link;

use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ValidateLinkPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateLinkPassword();
        });
    }

    /**
     * Validate the link password.
     *
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    protected function validateLinkPassword()
    {
        $id = $this->route('id');
        $link = Link::where('alias', $id)->first();

        if (!$link) {
            throw ValidationException::withMessages([
                'password' => ['Link bulunamadı.'],
            ]);
        }

        // IP + Link ID bazlı rate limiting
        $key = 'link_password_' . $id . '_' . $this->ip();

        // 5 deneme limiti, 10 dakika süreyle
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'password' => ['Çok fazla hatalı deneme. Lütfen ' . ceil($seconds / 60) . ' dakika sonra tekrar deneyin.'],
            ]);
        }

        // Şifre doğru mu kontrol et
        if (!Hash::check($this->password, $link->password)) {
            // Başarısız denemede sayacı artır
            RateLimiter::hit($key, 600); // 10 dakika (600 saniye)

            throw ValidationException::withMessages([
                'password' => ['Şifre hatalı.'],
            ]);
        }

        // Başarılı girişte sayacı sıfırla
        RateLimiter::clear($key);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password.required' => 'Şifre alanı gereklidir.',
        ];
    }
}
