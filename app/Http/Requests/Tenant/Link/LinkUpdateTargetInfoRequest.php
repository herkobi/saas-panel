<?php

namespace App\Http\Requests\Tenant\Link;

use App\Rules\ValidateBadWordsRule;
use App\Rules\ValidateUrlSafetyRule;
use Illuminate\Foundation\Http\FormRequest;

class LinkUpdateTargetInfoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'target_type' => $this->target_type === '' ? null : (int)$this->target_type,
        ]);
    }

    public function rules()
    {
        $rules = [
            'target_type' => ['nullable', 'integer', 'min:0', 'max:4'],
            'country_target' => ['nullable', 'array'],
            'platform_target' => ['nullable', 'array'],
            'language_target' => ['nullable', 'array'],
            'rotation_target' => ['nullable', 'array'],
        ];

        // Ülke hedefleme verileri validasyonu
        if ($this->has('country_target') && is_array($this->country_target)) {
            foreach ($this->country_target as $index => $countryData) {
                if (!empty($countryData)) {
                    $rules["country_target.{$index}.key"] = ['required', 'string'];
                    $rules["country_target.{$index}.value"] = ['required', 'url', 'max:2048', new ValidateBadWordsRule(), new ValidateUrlSafetyRule()];
                }
            }
        }

        // Platform hedefleme verileri validasyonu
        if ($this->has('platform_target') && is_array($this->platform_target)) {
            foreach ($this->platform_target as $index => $platformData) {
                if (!empty($platformData)) {
                    $rules["platform_target.{$index}.key"] = ['required', 'string'];
                    $rules["platform_target.{$index}.value"] = ['required', 'url', 'max:2048', new ValidateBadWordsRule(), new ValidateUrlSafetyRule()];
                }
            }
        }

        // Dil hedefleme verileri validasyonu
        if ($this->has('language_target') && is_array($this->language_target)) {
            foreach ($this->language_target as $index => $languageData) {
                if (!empty($languageData)) {
                    $rules["language_target.{$index}.key"] = ['required', 'string'];
                    $rules["language_target.{$index}.value"] = ['required', 'url', 'max:2048', new ValidateBadWordsRule(), new ValidateUrlSafetyRule()];
                }
            }
        }

        // Rotasyon hedefleme verileri validasyonu
        if ($this->has('rotation_target') && is_array($this->rotation_target)) {
            foreach ($this->rotation_target as $index => $rotationData) {
                if (!empty($rotationData)) {
                    $rules["rotation_target.{$index}.value"] = ['required', 'url', 'max:2048', new ValidateBadWordsRule(), new ValidateUrlSafetyRule()];
                }
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'target_type.integer' => 'Hedefleme türü geçerli bir değer olmalıdır.',
            'target_type.min' => 'Hedefleme türü geçerli bir değer olmalıdır.',
            'target_type.max' => 'Hedefleme türü geçerli bir değer olmalıdır.',

            'country.*.key.required' => 'Ülke kodu gereklidir.',
            'country.*.value.required' => 'Yönlendirme URL\'si gereklidir.',
            'country.*.value.url' => 'Geçerli bir URL giriniz.',
            'country.*.value.max' => 'URL en fazla :max karakter olabilir.',

            'platform.*.key.required' => 'Platform türü gereklidir.',
            'platform.*.value.required' => 'Yönlendirme URL\'si gereklidir.',
            'platform.*.value.url' => 'Geçerli bir URL giriniz.',
            'platform.*.value.max' => 'URL en fazla :max karakter olabilir.',

            'language.*.key.required' => 'Dil kodu gereklidir.',
            'language.*.value.required' => 'Yönlendirme URL\'si gereklidir.',
            'language.*.value.url' => 'Geçerli bir URL giriniz.',
            'language.*.value.max' => 'URL en fazla :max karakter olabilir.',

            'rotation.*.value.required' => 'Yönlendirme URL\'si gereklidir.',
            'rotation.*.value.url' => 'Geçerli bir URL giriniz.',
            'rotation.*.value.max' => 'URL en fazla :max karakter olabilir.',
        ];
    }
}
