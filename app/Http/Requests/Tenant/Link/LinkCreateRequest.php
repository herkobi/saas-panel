<?php

namespace App\Http\Requests\Tenant\Link;

use App\Rules\ValidateBadWordsRule;
use App\Rules\ValidateUrlSafetyRule;
use Illuminate\Foundation\Http\FormRequest;

class LinkCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'space_id' => (!$this->has('space_id') || $this->space_id === '') ? null : $this->space_id,
            'pixel_ids' => (!$this->has('pixel_ids') || $this->pixel_ids === '') ? null : $this->pixel_ids,
            'target_type' => (!$this->has('target_type') || $this->target_type === '') ? 0 : $this->target_type,
            'expiration_date' => (!$this->has('expiration_date') || $this->expiration_date === '') ? null : $this->expiration_date,
            'expiration_time' => (!$this->has('expiration_time') || $this->expiration_time === '') ? null : $this->expiration_time,
            'expiration_clicks' => (!$this->has('expiration_clicks') || $this->expiration_clicks === '') ? null : $this->expiration_clicks,
            'expiration_url' => (!$this->has('expiration_url') || $this->expiration_url === '') ? null : $this->expiration_url,
            'password' => (!$this->has('password') || $this->password === '') ? null : $this->password,
            'alias' => (!$this->has('alias') || $this->alias === '') ? null : $this->alias,
            'utm_source' => (!$this->has('utm_source') || $this->utm_source === '') ? null : $this->utm_source,
            'utm_medium' => (!$this->has('utm_medium') || $this->utm_medium === '') ? null : $this->utm_medium,
            'utm_campaign' => (!$this->has('utm_campaign') || $this->utm_campaign === '') ? null : $this->utm_campaign,
            'utm_term' => (!$this->has('utm_term') || $this->utm_term === '') ? null : $this->utm_term,
            'utm_content' => (!$this->has('utm_content') || $this->utm_content === '') ? null : $this->utm_content,
            'published_at_date' => (!$this->has('published_at_date') || $this->published_at_date === '') ? null : $this->published_at_date,
            'published_at_time' => (!$this->has('published_at_time') || $this->published_at_time === '') ? null : $this->published_at_time,
        ]);
    }

    public function rules()
    {
        $rules = [
            'url' => [
                'required_if:is_multiple,false',
                'required_without:multiple_urls',
                'max:2048',
                'url',
                new ValidateBadWordsRule(),
                new ValidateUrlSafetyRule()
            ],
            'is_multiple' => ['boolean'],
            'alias' => [
                'nullable',
                'alpha_dash',
                'max:255',
                'unique:links,alias',
                new ValidateBadWordsRule()
            ],
            'space_id' => ['nullable', 'exists:spaces,id'],
            'pixel_ids' => ['nullable', 'array'],
            'pixel_ids.*' => ['exists:pixels,id'],
            'disabled' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'max:128'],
            'target_type' => ['nullable', 'integer', 'min:0', 'max:4'],
            'goal' => ['nullable', 'integer', 'min:0'],
            'published_at_date' => ['nullable', 'date_format:Y-m-d'],
            'published_at_time' => ['nullable', 'date_format:H:i'],
        ];

        // Çoklu link validasyonu
        if ($this->input('is_multiple')) {
            $rules['multiple_urls'] = ['required', 'array'];
            $rules['multiple_urls.*'] = ['required', 'max:2048', 'url', new ValidateBadWordsRule(), new ValidateUrlSafetyRule()];
        }

        // Yayınlama tarihi/saati validasyonu
        if ($this->filled('published_at_date') || $this->filled('published_at_time')) {
            $rules['published_at_date'] = ['required', 'date_format:Y-m-d'];
            $rules['published_at_time'] = ['required', 'date_format:H:i'];
        }

        // Hedefleme validasyonu
        // Ülke verilerini kontrol et
        if ($this->has('country_target') && is_array($this->country_target)) {
            foreach ($this->country_target as $index => $countryData) {
                if (!empty($countryData)) {
                    $rules["country_target.{$index}.key"] = ['required', 'string'];
                    $rules["country_target.{$index}.value"] = ['required', 'url', 'max:2048', new ValidateBadWordsRule()];
                }
            }
        }

        // Platform verilerini kontrol et
        if ($this->has('platform_target') && is_array($this->platform_target)) {
            foreach ($this->platform_target as $index => $platformData) {
                if (!empty($platformData)) {
                    $rules["platform_target.{$index}.key"] = ['required', 'string'];
                    $rules["platform_target.{$index}.value"] = ['required', 'url', 'max:2048', new ValidateBadWordsRule()];
                }
            }
        }

        // Dil verilerini kontrol et
        if ($this->has('language_target') && is_array($this->language_target)) {
            foreach ($this->language_target as $index => $languageData) {
                if (!empty($languageData)) {
                    $rules["language_target.{$index}.key"] = ['required', 'string'];
                    $rules["language_target.{$index}.value"] = ['required', 'url', 'max:2048', new ValidateBadWordsRule()];
                }
            }
        }

        // Rotasyon verilerini kontrol et
        if ($this->has('rotation_target') && is_array($this->rotation_target)) {
            foreach ($this->rotation_target as $index => $rotationData) {
                if (!empty($rotationData)) {
                    $rules["rotation_target.{$index}.value"] = ['required', 'url', 'max:2048', new ValidateBadWordsRule()];
                }
            }
        }

        // Sona erme bilgileri validasyonu
        if ($this->filled('expiration_date') || $this->filled('expiration_time')) {
            $rules['expiration_date'] = ['required', 'date_format:Y-m-d'];
            $rules['expiration_time'] = ['required', 'date_format:H:i'];
        }

        if ($this->filled('expiration_url')) {
            $rules['expiration_url'] = ['url', 'max:2048', new ValidateBadWordsRule()];
        }

        if ($this->filled('expiration_clicks')) {
            $rules['expiration_clicks'] = ['integer', 'min:0', 'digits_between:0,9'];
        }

        // UTM parametreleri validasyonu
        $utmFields = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
        foreach ($utmFields as $field) {
            if ($this->filled($field)) {
                $rules[$field] = ['string', 'max:255'];
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'url.required_if' => 'Tekli link oluşturmak için URL girilmelidir.',
            'url.required_without' => 'URL veya çoklu URL\'lerden en az birini girmelisiniz.',
            'url.max' => 'URL en fazla :max karakter olabilir.',
            'url.url' => 'Geçerli bir URL formatı giriniz.',

            'alias.alpha_dash' => 'Takma ad yalnızca harf, sayı, tire ve alt çizgi içerebilir.',
            'alias.max' => 'Takma ad en fazla :max karakter olabilir.',
            'alias.unique' => 'Bu takma ad zaten kullanılıyor.',

            'space_id.string' => 'Geçerli bir alan seçiniz.',
            'space_id.exists' => 'Seçilen alan bulunamadı.',

            'pixel_ids.array' => 'Pikseller bir dizi olmalıdır.',
            'pixel_ids.*.exists' => 'Seçilen piksel bulunamadı.',

            'expiration_url.url' => 'Geçerli bir URL formatı giriniz.',
            'expiration_url.max' => 'Süre sonu URL\'si en fazla :max karakter olabilir.',

            'expiration_date.required' => 'Son tarih girildiğinde son saat de girilmelidir.',
            'expiration_date.date_format' => 'Geçerli bir tarih formatı giriniz.',

            'expiration_time.required' => 'Son saat girildiğinde son tarih de girilmelidir.',
            'expiration_time.date_format' => 'Geçerli bir saat formatı giriniz.',

            'expiration_clicks.integer' => 'Tıklama limiti bir sayı olmalıdır.',
            'expiration_clicks.min' => 'Tıklama limiti en az :min olmalıdır.',
            'expiration_clicks.digits_between' => 'Tıklama limiti en fazla :max basamaklı olmalıdır.',

            'goal.integer' => 'Lütfen rakam giriniz',
            'goal.min' => 'Hedefi en az 0 olarak giriniz',

            'published_at_date.required' => 'Yayınlama tarihi girildiğinde saat de girilmelidir.',
            'published_at_date.date_format' => 'Geçerli bir tarih formatı giriniz (YYYY-AA-GG).',

            'published_at_time.required' => 'Yayınlama saati girildiğinde tarih de girilmelidir.',
            'published_at_time.date_format' => 'Geçerli bir saat formatı giriniz (SS:DD).',
        ];
    }
}
