<?php

namespace App\Http\Requests\Tenant\Link;

use Illuminate\Foundation\Http\FormRequest;

class LinkUpdateUtmInfoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Switch durumlarını kontrol et
        $campaignEnabled = $this->boolean('utm_campaign_enabled');
        $termEnabled = $this->boolean('utm_term_enabled');
        $contentEnabled = $this->boolean('utm_content_enabled');

        // Verileri hazırla
        $this->merge([
            'utm_source' => $campaignEnabled ? ($this->utm_source === '' ? null : $this->utm_source) : null,
            'utm_medium' => $campaignEnabled ? ($this->utm_medium === '' ? null : $this->utm_medium) : null,
            'utm_campaign' => $campaignEnabled ? ($this->utm_campaign === '' ? null : $this->utm_campaign) : null,
            'utm_term' => $termEnabled ? ($this->utm_term === '' ? null : $this->utm_term) : null,
            'utm_content' => $contentEnabled ? ($this->utm_content === '' ? null : $this->utm_content) : null,
        ]);
    }

    public function rules()
    {
        return [
            'utm_source' => ['nullable', 'string', 'max:255'],
            'utm_medium' => ['nullable', 'string', 'max:255'],
            'utm_campaign' => ['nullable', 'string', 'max:255'],
            'utm_term' => ['nullable', 'string', 'max:255'],
            'utm_content' => ['nullable', 'string', 'max:255'],
            'utm_campaign_enabled' => ['boolean'],
            'utm_term_enabled' => ['boolean'],
            'utm_content_enabled' => ['boolean'],
        ];
    }

    public function messages()
    {
        return [
            'utm_source.string' => 'Kaynak (UTM Source) alanı metin olmalıdır.',
            'utm_source.max' => 'Kaynak (UTM Source) alanı en fazla :max karakter olabilir.',

            'utm_medium.string' => 'Medya (UTM Medium) alanı metin olmalıdır.',
            'utm_medium.max' => 'Medya (UTM Medium) alanı en fazla :max karakter olabilir.',

            'utm_campaign.string' => 'Kampanya (UTM Campaign) alanı metin olmalıdır.',
            'utm_campaign.max' => 'Kampanya (UTM Campaign) alanı en fazla :max karakter olabilir.',

            'utm_term.string' => 'Arama Terimi (UTM Term) alanı metin olmalıdır.',
            'utm_term.max' => 'Arama Terimi (UTM Term) alanı en fazla :max karakter olabilir.',

            'utm_content.string' => 'İçerik (UTM Content) alanı metin olmalıdır.',
            'utm_content.max' => 'İçerik (UTM Content) alanı en fazla :max karakter olabilir.',
        ];
    }
}
