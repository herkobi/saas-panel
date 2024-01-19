<?php

namespace App\Http\Requests\Admin\Profile;

use App\Models\Admin\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['string', 'max:255'],
            'username' => ['string', 'max:255', Rule::unique(Admin::class)->ignore($this->user()->id)],
            'email' => ['string', 'lowercase', 'email', 'max:255', Rule::unique(Admin::class)->ignore($this->user()->id)],
        ];
    }
}
