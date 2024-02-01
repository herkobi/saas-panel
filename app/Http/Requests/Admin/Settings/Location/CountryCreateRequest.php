<?php

namespace App\Http\Requests\Admin\Settings\Location;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CountryCreateRequest extends FormRequest
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
            'country' => ['required', 'string', 'max:255', Rule::unique('countries', 'country')],
            'code' => ['required']
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
            'status.required' => __('admin/settings/locations/country.status.required'),
            'status.integer' => __('admin/settings/locations/country.status.integer'),

            /**
             * Title Messages
             */
            'country.required' => __('admin/settings/locations/country.country.required'),
            'country.string' => __('admin/settings/locations/country.country.string'),
            'country.max' => __('admin/settings/locations/country.country.max255'),
            'country.unique' => __('admin/settings/locations/country.country.unique'),

            /**
             * Code Messages
             */
            'code.required' => __('admin/settings/locations/country.code.required'),

        ];
    }
}
