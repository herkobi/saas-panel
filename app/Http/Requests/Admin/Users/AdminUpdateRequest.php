<?php

namespace App\Http\Requests\Admin\Users;

use App\Enums\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class AdminUpdateRequest extends FormRequest
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
            'status' => ['required', new Enum(Admin::class)],
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($this->admin->id, 'id')],
            'username' => ['required', 'string', 'max:255', Rule::unique('admins', 'username')->ignore($this->admin->id, 'id')],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [

            /**
             * Status Messages
             */
            'status.required' => __('admin/users/user.status.required'),
            'status.integer' => __('admin/users/user.status.integer'),

            /**
             * Name Messages
             */
            'name.required' => __('admin/users/user.name.required'),
            'name.string' => __('admin/users/user.name.string'),
            'name.max' => __('admin/users/user.name.max'),

            /**
             * Surname Messages
             */
            'surname.required' => __('admin/users/user.surname.required'),
            'surname.string' => __('admin/users/user.surname.string'),
            'surname.max' => __('admin/users/user.surname.max'),

            /**
             * Title Messages
             */
            'title.required' => __('admin/plans/plan.title.required'),
            'title.string' => __('admin/plans/plan.title.string'),
            'title.max' => __('admin/plans/plan.title.max'),

            /**
             * Username Messages
             */
            'username.required' => __('admin/users/user.username.required'),
            'username.string' => __('admin/users/user.username.string'),
            'username.max' => __('admin/users/user.username.max'),
            'username.unique' => __('admin/users/user.username.unique'),

            /**
             * Email Messages
             */
            'email.required' => __('admin/users/user.email.required'),
            'email.email' => __('admin/users/user.email.email'),
            'email.max' => __('admin/users/user.email.max'),
            'email.unique' => __('admin/users/user.email.unique'),
        ];
    }
}
