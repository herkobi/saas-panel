<?php

namespace App\Http\Requests\Tenant\Campaign;

use App\Enums\CampaignStatus;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CampaignCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_featured' => $this->hasBoolean('is_featured', false),
            'has_form' => $this->hasBoolean('has_form', false),
            'form_id' => $this->nullableString('form_id'),
            'meta_title' => $this->nullableString('meta_title'),
            'meta_description' => $this->nullableString('meta_description'),
            'published_at' => $this->shouldPublish() ? now() : $this->published_at,
        ]);
    }

    protected function hasBoolean($key, $default = false): bool
    {
        return $this->has($key) ? (bool) $this->$key : $default;
    }

    protected function nullableString($key)
    {
        return $this->has($key) && $this->$key !== '' ? $this->$key : null;
    }

    protected function shouldPublish()
    {
        return $this->input('status') == CampaignStatus::ACTIVE->value && empty($this->published_at);
    }

    public function rules()
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'terms' => ['nullable', 'string'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'apply_date' => ['nullable', 'date', 'after_or_equal:end_date'],
            'apply_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::enum(CampaignStatus::class)],
            'external_link' => ['nullable', 'url', 'max:2048'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],

            'is_featured' => ['boolean'],
            'has_form' => ['boolean']
        ];

        // Koşullu kuralları ekle
        $this->addConditionalRules($rules);

        return $rules;
    }

    protected function addConditionalRules(&$rules)
    {
        // apply_name dolu ise apply_date zorunlu
        if ($this->filled('apply_name')) {
            $rules['apply_date'] = ['required', 'date', 'after_or_equal:end_date'];
        }

        // has_form true ise form_id zorunlu
        if ($this->input('has_form')) {
            $rules['form_id'] = ['required', 'string', 'exists:forms,id'];
        }

        // status ACTIVE ise published_at zorunlu
        if ($this->input('status') == CampaignStatus::ACTIVE->value) {
            $rules['published_at'] = ['required', 'date'];
        }

        // Meta alanları
        $rules['meta_title'] = ['nullable', 'string', 'max:255'];
        $rules['meta_description'] = ['nullable', 'string', 'max:255'];
    }

    public function messages()
    {
        return [
            'title.required' => 'Kampanya başlığı gereklidir.',
            'title.max' => 'Kampanya başlığı en fazla :max karakter olabilir.',

            'start_date.required' => 'Başlangıç tarihi gereklidir.',
            'start_date.date' => 'Geçerli bir başlangıç tarihi giriniz.',
            'start_date.after_or_equal' => 'Başlangıç tarihi bugün veya daha sonrası olmalıdır.',

            'end_date.required' => 'Bitiş tarihi gereklidir.',
            'end_date.date' => 'Geçerli bir bitiş tarihi giriniz.',
            'end_date.after' => 'Bitiş tarihi başlangıç tarihinden sonra olmalıdır.',

            'apply_date.required' => 'Sonuç tarihi gereklidir.',
            'apply_date.date' => 'Geçerli bir sonuç tarihi giriniz.',
            'apply_date.after_or_equal' => 'Sonuç tarihi, bitiş tarihinden sonra olmalıdır.',

            'status.required' => 'Durum seçimi gereklidir.',

            'external_link.url' => 'Geçerli bir URL giriniz.',
            'external_link.max' => 'URL en fazla :max karakter olabilir.',

            'published_at.required' => 'Yayınlanma tarihi gereklidir.',
            'published_at.date' => 'Geçerli bir yayınlanma tarihi giriniz.',

            'form_id.required' => 'Form seçimi gereklidir.',
            'form_id.exists' => 'Seçilen form bulunamadı.',

            'image.required' => 'Lütfen kampanya görseli yükleyiniz',
            'image.image' => 'Lütfen geçerli bir görsel yükleyiniz',
            'image.mimes' => 'Lütfen sadece jpeg, png ve jpg uzantısına sahip görsel yükleyiniz',
            'image:max' => 'Lütfen en fazla 1MB\'lık görsel yükleyiniz',
        ];
    }
}
