<?php

namespace App\Http\Requests\Admin\Tools\Orderstatus;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Status;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class OrderstatusUpdateRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', Rule::unique('orderstatuses', 'title')->ignore($this->orderstatus, 'id')],
            'description' => ['nullable', 'string', 'max:255'],
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
            'title.unique' => 'Bu isimde kayıtlı tanım bulunmamaktadır.',

            'description.string' => 'Lütfen geçerli bir içerik giriniz',
            'description.max' => 'Lütfen daha kısa içerik giriniz'
        ];
    }
}
