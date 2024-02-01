<?php

namespace App\Http\Requests\Admin\Settings\Tax;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class TaxCreateRequest extends FormRequest
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
            'status' => ['required', 'integer', new Enum(Status::class)],
            'title' => ['required', 'string', 'max:255', Rule::unique('taxes', 'title')],
            'desc' => ['required'],
            'value' => ['required', 'numeric'],
            'country_id' => ['exists:countries,id', 'required']
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
            'status.required' => __('admin/settings/tax.status.required'),
            'status.integer' => __('admin/settings/tax.status.integer'),

            /**
             * Title Messages
             */
            'title.required' => __('admin/settings/tax.title.required'),
            'title.string' => __('admin/settings/tax.title.string'),
            'title.max' => __('admin/settings/tax.title.max'),
            'title.unique' => __('admin/settings/tax.title.unique'),

            /**
             * Desc Messages
             */
            'desc.required' => __('admin/settings/tax.text.required'),

            /**
             * Value Messages
             */
            'value.required' => __('admin/settings/tax.value.required'),
            'value.numeric' => __('admin/settings/tax.value.numeric'),

            /**
             * Country ID Messages
             */
            'country_id.exists' => __('admin/settings/tax.country_id.exists'),
            'country_id.required' => __('admin/settings/tax.country_id.required'),

        ];
    }
}
