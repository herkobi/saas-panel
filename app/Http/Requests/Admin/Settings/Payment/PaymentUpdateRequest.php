<?php

namespace App\Http\Requests\Admin\Settings\Payment;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PaymentUpdateRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', Rule::unique('payments', 'title')->ignore($this->payment->id, 'id')],
            'desc' => ['string']
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
            'status.required' => __('admin/settings/payments.status.required'),
            'status.integer' => __('admin/settings/payments.status.integer'),

            /**
             * Title Messages
             */
            'title.required' => __('admin/settings/payments.title.required'),
            'title.string' => __('admin/settings/payments.title.string'),
            'title.max:255' => __('admin/settings/payments.title.max'),
            'title.unique' => __('admin/settings/payments.title.unique'),

            /**
             * Desc Messages
             */
            'desc.string' => __('admin/settings/payments.desc.string'),
        ];
    }

}
