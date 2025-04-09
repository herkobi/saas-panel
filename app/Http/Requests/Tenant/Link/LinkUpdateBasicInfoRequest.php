<?php

namespace App\Http\Requests\Tenant\Link;

use App\Rules\ValidateBadWordsRule;
use App\Rules\ValidateUrlSafetyRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LinkUpdateBasicInfoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Goal switch durumunu kontrol et
        $goalEnabled = $this->boolean('goal_enabled');

        // Alias varsa, slug formatına dönüştür
        if ($this->filled('alias')) {
            $this->merge([
                'alias' => Str::slug($this->alias),
            ]);
        }

        // Switch durumlarına göre değerleri ayarla
        $this->merge([
            'space_id' => $this->space_id === '' ? null : $this->space_id,
            'goal' => $goalEnabled ? ($this->goal === '' ? null : $this->goal) : null,
            'published_at_date' => (!$this->has('published_at_date') || $this->published_at_date === '') ? null : $this->published_at_date,
            'published_at_time' => (!$this->has('published_at_time') || $this->published_at_time === '') ? null : $this->published_at_time,
        ]);
    }

    public function rules()
    {
        $rules = [
            'alias' => [
                'required',
                'alpha_dash',
                'max:255',
                Rule::unique('links', 'alias')->ignore($this->link, 'id'),
                new ValidateBadWordsRule()
            ],
            'space_id' => ['nullable', 'exists:spaces,id'],
            'goal' => ['nullable', 'integer', 'min:0'],
            'goal_enabled' => ['boolean'],
            'published_at_date' => ['nullable', 'date_format:Y-m-d'],
            'published_at_time' => ['nullable', 'date_format:H:i'],
        ];

        // Yayınlama tarihi/saati validasyonu
        if ($this->filled('published_at_date') || $this->filled('published_at_time')) {
            $rules['published_at_date'] = ['required', 'date_format:Y-m-d'];
            $rules['published_at_time'] = ['required', 'date_format:H:i'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'alias.required' => 'Lütfen takma ad giriniz',
            'alias.alpha_dash' => 'Takma ad yalnızca harf, sayı, tire ve alt çizgi içerebilir.',
            'alias.max' => 'Takma ad en fazla :max karakter olabilir.',
            'alias.unique' => 'Bu takma ad zaten kullanılıyor.',
            'space_id.exists' => 'Seçilen alan bulunamadı.',
            'goal.integer' => 'Lütfen rakam giriniz',
            'published_at_date.required' => 'Yayınlama tarihi girildiğinde saat de girilmelidir.',
            'published_at_date.date_format' => 'Geçerli bir tarih formatı giriniz (YYYY-AA-GG).',
            'published_at_time.required' => 'Yayınlama saati girildiğinde tarih de girilmelidir.',
            'published_at_time.date_format' => 'Geçerli bir saat formatı giriniz (SS:DD).',
        ];
    }
}
