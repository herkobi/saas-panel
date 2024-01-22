<?php

namespace App\Http\Requests\Admin\Gateways;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class GatewayUpdateRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'value' => ['string']
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
            'status.required' => __('admin/settings/gateways.status.required'),
            'status.integer' => __('admin/settings/gateways.status.integer'),

            /**
             * Title Messages
             */
            'title.required' => __('admin/settings/gateways.title.required'),
            'title.string' => __('admin/settings/gateways.title.string'),
            'title.max:255' => __('admin/settings/gateways.title.max'),
            'title.unique' => __('admin/settings/gateways.title.unique'),

            /**
             * Code Messages
             */
            'value.required' => __('admin/settings/gateways.value.required'),
        ];
    }

}
