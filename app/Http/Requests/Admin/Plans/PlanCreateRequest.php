<?php

namespace App\Http\Requests\Admin\Plans;

use App\Enums\Period;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PlanCreateRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', Rule::unique('plans', 'title')],
            'name' => [Rule::unique('plans', 'name')],
            'price' => ['required', 'integer'],
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'periodicity_type' => ['required', 'integer', new Enum(Period::class)],
            'periodicity' => ['required', 'integer'],
            'grace_days' => ['nullable', 'integer'],
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
            'status.required' => __('admin/plans/plans.status.required'),

            /**
             * Title Messages
             */
            'title.required' => __('admin/plans/plans.title.required'),
            'title.string' => __('admin/plans/plans.title.string'),
            'title.max' => __('admin/plans/plans.title.max'),
            'title.unique' => __('admin/setting/contract.title.unique'),

            /**
             * Name Messages
             */
            'name.unique' => __('admin/setting/contract.name.unique'),

            /**
             * Price Messages
             */
            'price.required' => __('admin/plans/plans.price.required'),
            'price.integer' => __('admin/plans/plans.price.integer'),

            /**
             * Currency ID Messages
             */
            'currency_id.required' => __('admin/plans/plans.currency_id.required'),
            'currency_id.integer' => __('admin/plans/plans.currency_id.integer'),
            'currency_id.exists' => __('admin/plans/plans.currency_id.exists'),

            /**
             * Period Count Messages
             */
            'periodicity.required' => __('admin/plans/plans.periodicity.required'),
            'periodicity.integer' => __('admin/plans/plans.periodicity.integer'),

            /**
             * Period Type Messages
             */
            'periodicity_type.required' => __('admin/plans/plans.periodicity_type.required'),
            'periodicity_type.integer' => __('admin/plans/plans.periodicity_type.integer'),

            /**
             * Grace Days Messages
             */
            'grace_days.integer' => __('admin/plans/plans.grace_days.integer'),
        ];
    }
}
