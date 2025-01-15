<?php

namespace App\Http\Requests\Admin\Tools\Tax;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Status;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class TaxUpdateRequest extends FormRequest
{
    public function authorize()
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
            'status' => ['required', new Enum(Status::class)],
            'title' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                Rule::unique('taxes')
                    ->where(function ($query) {
                        return $query->where('id', '!=', $this->route('id'))
                            ->where('code', $this->code)
                            ->where('value', $this->value)
                            ->whereExists(function ($subquery) {
                                $subquery->from('tax_regions')
                                    ->whereColumn('tax_regions.tax_id', 'taxes.id')
                                    ->where('tax_regions.country_id', $this->input('regions.0.country_id'))
                                    ->where(function($q) {
                                        if ($this->input('regions.0.state_id')) {
                                            $q->where('tax_regions.state_id', $this->input('regions.0.state_id'));
                                        } else {
                                            $q->whereNull('tax_regions.state_id');
                                        }
                                    });
                            });
                    })
            ],
            'value' => ['required', 'numeric'],
            'regions' => ['required', 'array'],
            'regions.*.country_id' => ['required', 'exists:countries,id'],
            'regions.*.state_id' => ['nullable', 'exists:states,id']
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Durum alanı zorunludur.',
            'status.enum' => 'Geçersiz durum değeri.',

            'title.required' => 'Başlık alanı zorunludur.',
            'title.string' => 'Başlık metin formatında olmalıdır.',
            'title.max' => 'Başlık en fazla 255 karakter olabilir.',

            'code.required' => 'Kod alanı zorunludur.',
            'code.string' => 'Kod metin formatında olmalıdır.',
            'code.unique' => 'Bu vergi kodu, oranı ve bölgesi için kayıt zaten mevcut.',

            'value.required' => 'Değer alanı zorunludur.',
            'value.numeric' => 'Değer sayısal olmalıdır.',

            'regions.required' => 'En az bir bölge seçmelisiniz.',
            'regions.array' => 'Bölge seçimi geçersiz formatta.',
            'regions.*.country_id.required' => 'Ülke seçimi zorunludur.',
            'regions.*.country_id.exists' => 'Seçilen ülke geçersiz.',
            'regions.*.state_id.exists' => 'Seçilen şehir geçersiz.'
        ];
    }
}
