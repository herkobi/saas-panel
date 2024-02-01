<?php

namespace App\Http\Requests\Admin\Gateways\Bac;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class BacCreateRequest extends FormRequest
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
            'account_name' => ['required', 'string', 'max:255'],
            'account_bank' => ['required', 'string', 'max:255'],
            'account_branch' => ['nullable', 'numeric', 'required_with:account_number'],
            'account_number' => ['nullable', 'numeric', 'required_with:account_branch'],
            'account_iban' => ['nullable', 'required_without_all:account_branch,account_number'],
            'account_swift' => ['nullable'],
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
            'status.required' => __('admin/gateways/bac.status.required'),
            'status.integer' => __('admin/gateways/bac.status.integer'),

            /**
             * Title Messages
             */
            'title.required' => __('admin/gateways/bac.title.required'),
            'title.string' => __('admin/gateways/bac.title.string'),
            'title.max' => __('admin/gateways/bac.title.max255'),

            /**
             * Currency Messages
             */
            'currency_id.exists' => __('admin/gateways/bac.currency_id.exists'),
            'currency_id.numeric' => __('admin/gateways/bac.currency_id.numeric'),

            /**
             * Account Name Messages
             */
            'account_name.required' => __('admin/gateways/bac.account_name.required'),
            'account_name.string' => __('admin/gateways/bac.account_name.string'),
            'account_name.max' => __('admin/gateways/bac.account_name.max255'),

            /**
             * Account Bank Messages
             */
            'account_bank.required' => __('admin/gateways/bac.account_bank.required'),
            'account_bank.string' => __('admin/gateways/bac.account_bank.string'),
            'account_bank.max' => __('admin/gateways/bac.account_bank.max255'),

            /**
             * Account Branch Messages
             */
            'account_branch.numeric' => __('admin/gateways/bac.account_branch.numeric'),
            'account_branch.required_with' => __('admin/gateways/bac.account_branch.required_with'),

            /**
             * Account Number Messages
             */
            'account_number.numeric' => __('admin/gateways/bac.account_number.numeric'),
            'account_number.required_with' => __('admin/gateways/bac.account_number.required_with'),

            /**
             * Account IBAN Messages
             */
            'account_iban.required_without_all' => __('admin/gateways/bac.account_iban.required_without_all'),
        ];
    }
}
