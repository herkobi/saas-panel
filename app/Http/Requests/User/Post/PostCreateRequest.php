<?php

namespace App\Http\Requests\User\Post;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Status;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PostCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100', Rule::unique('posts', 'title')],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Sayfa adı alanı zorunludur.',
            'title.string' => 'Sayfa adı metin olmalıdır.',
            'title.max' => 'Sayfa adı en fazla :max karakter olabilir.',
            'title.unique' => 'Bu sayfa adı zaten kayıtlı.',
        ];
    }
}
