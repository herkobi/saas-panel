<?php

namespace App\Http\Requests\Tenant\Link;

use App\Rules\ValidateBadWordsRule;
use App\Rules\ValidateUrlSafetyRule;
use Illuminate\Foundation\Http\FormRequest;

class LinkUpdateExtraInfoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Switch'lerin durumlarını kontrol eden alanlar
        $passwordEnabled = $this->boolean('password_enabled');
        $expirationClicksEnabled = $this->boolean('expiration_clicks_enabled');
        $expirationDateEnabled = $this->boolean('expiration_date_enabled');
        $expirationUrlEnabled = $this->boolean('expiration_url_enabled');
        $pixelsEnabled = $this->boolean('pixels_enabled');

        // Switch'lerin durumuna göre ilgili alanları null olarak ayarla
        $this->merge([
            'password' => $passwordEnabled ? $this->password : null,
            'password_hint' => $passwordEnabled ? $this->password_hint : null, // Yeni eklenen alan
            'expiration_clicks' => $expirationClicksEnabled ? $this->expiration_clicks : null,
            'expiration_date' => $expirationDateEnabled ? $this->expiration_date : null,
            'expiration_time' => $expirationDateEnabled ? $this->expiration_time : null,
            'expiration_url' => $expirationUrlEnabled ? $this->expiration_url : null,
            'pixel_ids' => $pixelsEnabled ? $this->pixel_ids : [],
        ]);
    }

    public function rules()
    {
        return [
            'password' => ['nullable', 'string', 'max:128'],
            'password_hint' => ['nullable', 'string', 'max:255'], // Yeni eklenen alan için kural
            'expiration_clicks' => ['nullable', 'integer', 'min:1'],
            'expiration_date' => ['nullable', 'date_format:Y-m-d'],
            'expiration_time' => ['nullable', 'date_format:H:i'],
            'expiration_url' => [
                'nullable',
                'url',
                'max:2048',
                new ValidateBadWordsRule(),
                new ValidateUrlSafetyRule()
            ],
            'pixel_ids' => ['nullable', 'array'],
            'pixel_ids.*' => ['exists:pixels,id'],
        ];
    }

    public function messages()
    {
        return [
            'password.max' => 'Şifre en fazla :max karakter olabilir.',
            'password_hint.max' => 'Şifre ipucu en fazla :max karakter olabilir.',

            'expiration_clicks.integer' => 'Tıklama limiti bir sayı olmalıdır.',
            'expiration_clicks.min' => 'Tıklama limiti en az :min olmalıdır.',

            'expiration_date.date_format' => 'Geçerli bir tarih formatı giriniz (YYYY-AA-GG).',
            'expiration_time.date_format' => 'Geçerli bir saat formatı giriniz (SS:DD).',

            'expiration_url.url' => 'Geçerli bir URL formatı giriniz.',
            'expiration_url.max' => 'Süre sonu URL\'si en fazla :max karakter olabilir.',

            'pixel_ids.array' => 'Pikseller bir dizi olmalıdır.',
            'pixel_ids.*.exists' => 'Seçilen piksel bulunamadı.',
        ];
    }
}
