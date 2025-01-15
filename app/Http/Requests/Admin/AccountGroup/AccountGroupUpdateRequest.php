<?php

namespace App\Http\Requests\Admin\AccountGroup;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Status;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class AccountGroupUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100', Rule::unique('account_groups', 'title')->ignore($this->accountgroup, 'id')],
            'color' => ['required', 'string', 'max:25'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Kategori adı alanı zorunludur.',
            'title.string' => 'Geçerli bir kategori adı giriniz.',
            'title.max' => 'Daha kısa kategori adı giriniz.',
            'title.unique' => 'Bu isimde kayıtlı bir kategori adı bulunmaktadır.',

            'color.required' => 'Grup rengini giriniz.',
            'color.string' => 'Geçerli bir renk kodu giriniz.',
            'color.max' => 'Daha kısa bir renk kodu giriniz.',
        ];
    }
}
