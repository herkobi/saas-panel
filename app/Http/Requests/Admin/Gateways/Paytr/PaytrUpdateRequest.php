<?php

namespace App\Http\Requests\Admin\Gateways\Paytr;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PaytrUpdateRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'desc' => ['nullable'],
            'currency_id' => ['exists:currencies,id', 'numeric'],
            'logo' => ['nullable'],
            'merchant_id' => ['required',],
            'merchant_key' => ['required'],
            'merchant_salt' => ['required'],
            'merchant_ok_url' => ['required', 'url'],
            'merchant_fail_url' => ['required', 'url'],
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
            'status.required' => __('admin/gateways/paytr.status.required'),
            'status.integer' => __('admin/gateways/paytr.status.integer'),

            /**
             * Title Messages
             */
            'title.required' => __('admin/gateways/paytr.title.required'),
            'title.string' => __('admin/gateways/paytr.title.string'),
            'title.max' => __('admin/gateways/paytr.title.max255'),

            /**
             * Currency Messages
             */
            'currency_id.exists' => __('admin/gateways/paytr.currency_id.exists'),
            'currency_id.numeric' => __('admin/gateways/paytr.currency_id.numeric'),

            /**
             * Account ID Messages
             */
            'merchant_id.required' => __('admin/gateways/paytr.merchant_id.required'),

            /**
             * Account Key Messages
             */
            'merchant_key.required' => __('admin/gateways/paytr.merchant_key.required'),

            /**
             * Security Code Messages
             */
            'merchant_salt.required' => __('admin/gateways/paytr.merchant_salt.required'),

            /**
             * Success Return URL Messages
             */
            'merchant_ok_url.required' => __('admin/gateways/paytr.merchant_ok_url.required'),

            /**
             * Error Return URL Messages
             */
            'merchant_fail_url.required' => __('admin/gateways/paytr.merchant_fail_url.required'),

        ];
    }
}
