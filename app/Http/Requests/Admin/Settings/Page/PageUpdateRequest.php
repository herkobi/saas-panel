<?php

namespace App\Http\Requests\Admin\Settings\Page;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PageUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Currency\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(Status::class)],
            'title' => ['required', 'string', 'max:255', Rule::unique('pages', 'title')->ignore($this->page->id, 'id')],
            'text' => ['max:255']
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
            'status.required' => __('admin/settings/pages.status.required'),
            'status.integer' => __('admin/settings/pages.status.integer'),

            /**
             * Title Messages
             */
            'title.required' => __('admin/settings/pages.title.required'),
            'title.string' => __('admin/settings/pages.title.string'),
            'title.max:255' => __('admin/settings/pages.title.max255'),
            'title.unique' => __('admin/settings/pages.title.unique'),

            /**
             * Desc Messages
             */
            'text.required' => __('admin/settings/pages.text.required'),
        ];
    }

}
