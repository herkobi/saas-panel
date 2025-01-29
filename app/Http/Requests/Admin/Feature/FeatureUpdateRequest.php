<?php

namespace App\Http\Requests\Admin\Feature;

use Illuminate\Foundation\Http\FormRequest;

class FeatureUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'consumable' => $this->boolean('consumable'),
            'quota' => $this->boolean('quota'),
            'postpaid' => $this->boolean('postpaid'),
            'periodicity_type' => $this->periodicity_type,
            'periodicity' => $this->periodicity
        ]);
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'consumable' => ['required', 'boolean'],
            'quota' => ['required', 'boolean'],
            'postpaid' => ['required', 'boolean'],
        ];

        if ($this->boolean('consumable')) {
            $rules['quota'] = [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    $hasPeriodicity = !is_null($this->periodicity_type) && !is_null($this->periodicity);

                    if ($value && $hasPeriodicity) {
                        $fail('Kota takibi seçiliyken periyodik yenileme seçilemez.');
                    }

                    if (!$value && !$hasPeriodicity) {
                        $fail('Tüketilebilir özellikler için ya kota takibi ya da periyodik yenileme seçilmelidir.');
                    }
                }
            ];

            if (!$this->boolean('quota')) {
                $rules['periodicity_type'] = ['required', 'string'];
                $rules['periodicity'] = ['required', 'integer', 'min:1'];
            }
        }

        return $rules;
    }

    public function messages(): array
    {
       return [
           'name.required' => 'Lütfen özellik adını giriniz',
           'name.string' => 'Lütfen geçerli bir özellik adı giriniz',
           'name.max' => 'Lütfen daha kısa bir özellik adı giriniz',
           'consumable.required' => 'Kullanım takibi seçimi yapmalısınız',
           'consumable.boolean' => 'Kullanım takibi değeri geçersiz',
           'quota.required' => 'Kota takibi seçimi yapmalısınız',
           'quota.boolean' => 'Kota takibi değeri geçersiz',
           'postpaid.required' => 'Sonradan ödeme seçimi yapmalısınız',
           'postpaid.boolean' => 'Sonradan ödeme değeri geçersiz',
           'periodicity_type.required' => 'Periyodik yenileme için periyot türü seçmelisiniz',
           'periodicity_type.string' => 'Geçersiz periyot türü',
           'periodicity.required' => 'Periyodik yenileme için periyot değeri girmelisiniz',
           'periodicity.integer' => 'Periyot değeri sayı olmalıdır',
           'periodicity.min' => 'Periyot değeri en az 1 olmalıdır',
       ];
    }
}
