<?php

namespace App\Http\Requests\Admin\Settings\AgreementVersion;

use App\Enums\UserType;
use App\Models\Agreement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgreementVersionPublishRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'require_acceptance' => [
                'required',
                Rule::in([true, false]),
            ],
            'block_access' => [
                'required',
                Rule::in([true, false]),
            ],
            'send_notification' => [
                'required',
                Rule::in([true, false]),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'require_acceptance.required' => 'Kabul zorunluluğu alanı gereklidir.',
            'require_acceptance.in' => 'Kabul zorunluluğu alanı geçersiz.',
            'block_access.required' => 'Erişim engelleme alanı gereklidir.',
            'block_access.in' => 'Erişim engelleme alanı geçersiz.',
            'send_notification.required' => 'Bildirim gönderme alanı gereklidir.',
            'send_notification.in' => 'Bildirim gönderme alanı geçersiz.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $agreement = Agreement::where('slug', $this->route('agreement'))->firstOrFail();

        if ($agreement->user_type !== UserType::USER) {
            $this->merge([
                'require_acceptance' => $this->boolean('require_acceptance'),
                'block_access' => false,
                'send_notification' => $this->boolean('send_notification'),
            ]);
        } else {
            $this->merge([
                'require_acceptance' => $this->boolean('require_acceptance'),
                'block_access' => $this->boolean('block_access'),
                'send_notification' => $this->boolean('send_notification'),
            ]);
        }
    }
}
