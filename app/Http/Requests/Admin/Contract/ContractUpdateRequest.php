<?php

namespace App\Http\Requests\Admin\Contract;

use App\Enums\ContractType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ContractUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
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
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('contracts')->ignore($this->route('contract')),
            ],
            'type' => ['required', new Enum(ContractType::class)],
            'content' => ['required', 'string'],
            'date' => ['required', 'string'],
            'status' => ['boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->slug === null && $this->title) {
            $this->merge([
                'slug' => Str::slug($this->title),
            ]);
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Sözleşme başlığı zorunludur.',
            'title.max' => 'Sözleşme başlığı en fazla :max karakter olabilir.',
            'slug.unique' => 'Bu URL kodu zaten kullanılmakta, lütfen başka bir URL kodu seçin.',
            'content.required' => 'Sözleşme içeriği zorunludur.',
            'date.required' => 'Sözleşme güncelleme tarihini giriniz.',
        ];
    }
}
