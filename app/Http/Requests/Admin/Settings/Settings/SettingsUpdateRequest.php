<?php

namespace App\Http\Requests\Admin\Settings\Settings;

use Illuminate\Foundation\Http\FormRequest;

class SettingsUpdateRequest extends FormRequest
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
            'slogan' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable','image', 'max:1024', 'mimes:jpg,jpeg,png,svg'],
            'favicon' => ['nullable','image', 'max:512', 'mimes:png'],
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
             * Title Messages
             */
            'title.required' => __('admin/settings/general.title.required'),
            'title.string' => __('admin/settings/general.title.string'),
            'title.max' => __('admin/settings/general.title.max'),

            /**
             * Slogan Messages
             */
            'slogan.string' => __('admin/settings/general.slogan.string'),
            'slogan.max' => __('admin/settings/general.slogan.max'),

            /**
             * Logo Messages
             */
            'logo.image' => __('admin/settings/general.logo.image'),
            'logo.max' => __('admin/settings/general.logo.max'),
            'logo.mimes' => __('admin/settings/general.logo.mimes'),

            /**
             * Favicon Messages
             */
            'favicon.image' => __('admin/settings/general.favicon.image'),
            'favicon.max' => __('admin/settings/general.favicon.max'),
            'favicon.mimes' => __('admin/settings/general.favicon.mimes'),
        ];
    }
}
