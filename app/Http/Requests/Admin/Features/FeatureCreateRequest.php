<?php

namespace App\Http\Requests\Admin\Features;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class FeatureCreateRequest extends FormRequest
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
            'status' => ['required', new Enum(Status::class)],
            'title' => ['required', 'string', 'max:255', Rule::unique('features', 'title')],
            'name' => [Rule::unique('features', 'name')],
            'consumable' => ['required', 'in:0,1'],
            'quota' => ['in:0,1'],
            'postpaid' => ['in:0,1'],
            'periodicity' => ['nullable', 'required_if:consumable,true', 'integer'],
            'periodicity_type' => ['nullable', 'required_if:consumable,true', 'in:Day,Week,Month,Year'], // Örneğin, gün, hafta, ay
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
            'status.required' => __('admin/plans/plan.status.required'),

            /**
             * Title Messages
             */
            'title.required' => __('admin/plans/plan.title.required'),
            'title.string' => __('admin/plans/plan.title.string'),
            'title.max:255' => __('admin/plans/plan.title.max255'),
            'title.unique' => __('admin/setting/contract.title.unique'),

            /**
             * Name Messages
             */
            'name.unique' => __('admin/setting/contract.name.unique'),

            /**
             * Consumable Messages
             */
            'consumable.required' => __('admin/plans/plan.consumable.required'),
            'consumable.in' => __('admin/plans/plan.consumable.in'),

            /**
             * Quota Messages
             */
            'quota.in' => __('admin/plans/plan.quota.in'),

            /**
             * Postpaid Messages
             */
            'postpaid.in' => __('admin/plans/plan.postpaid.in'),

            /**
             * Period Count Messages
             */
            'periodicity.nullable' => __('admin/plans/plan.periodicity.nullable'),
            'periodicity.required_if:consumable,true' => __('admin/plans/plan.periodicity.required_if'),
            'periodicity.integer' => __('admin/plans/plan.periodicity.integer'),

            /**
             * Period Type Messages
             */
            'periodicity_type.nullable' => __('admin/plans/plan.periodicity_type.nullable'),
            'periodicity_type.required_if:consumable,true' => __('admin/plans/plan.periodicity_type.required_if'),
            'periodicity_type.in:day,week,month,year' => __('admin/plans/plan.periodicity_type.in'),

        ];
    }
}
