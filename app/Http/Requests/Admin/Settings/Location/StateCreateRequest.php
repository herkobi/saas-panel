<?php

namespace App\Http\Requests\Admin\Settings\Location;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StateCreateRequest extends FormRequest
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
            'state' => ['required', 'string', 'max:255', Rule::unique('states', 'state')],
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
            'status.required' => __('admin/settings/locations/state.status.required'),
            'status.integer' => __('admin/settings/locations/state.status.integer'),

            /**
             * Title Messages
             */
            'state.required' => __('admin/settings/locations/state.state.required'),
            'state.string' => __('admin/settings/locations/state.state.string'),
            'state.max' => __('admin/settings/locations/state.state.max255'),
            'state.unique' => __('admin/settings/locations/state.state.unique'),

            /**
             * Country ID Messages
             */
            'country_id.exists' => __('admin/settings/locations/state.country_id.exists'),
            'country_id.required' => __('admin/settings/locations/state.country_id.required'),

        ];
    }
}
