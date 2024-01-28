<?php

namespace App\Http\Requests\Admin\Settings\Currency;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CurrencyUpdateRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', Rule::unique('currencies', 'title')->ignore($this->currency->id, 'id')],
            'symbol' => ['required'],
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
            'status.required' => __('admin/settings/currencies.status.required'),
            'status.integer' => __('admin/settings/currencies.status.integer'),

            /**
             * Title Messages
             */
            'title.required' => __('admin/settings/currencies.title.required'),
            'title.string' => __('admin/settings/currencies.title.string'),
            'title.max:255' => __('admin/settings/currencies.title.max'),
            'title.unique' => __('admin/settings/currencies.title.unique'),

            /**
             * Symbol Messages
             */
            'symbol.required' => __('admin/settings/currencies.text.required'),

            /**
             * Code Messages
             */
            'code.required' => __('admin/settings/currencies.text.required'),
        ];
    }

}
