<?php

namespace App\Http\Requests\Admin\Features;

use App\Enums\Period;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class FeatureUpdateRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', Rule::unique('features', 'title')->ignore($this->feature->id, 'id')],
            'name' => [Rule::unique('features', 'name')->ignore($this->feature->id, 'id')],
            'consumable' => ['required', 'in:0,1', 'integer'],
            'quota' => ['required','in:0,1', 'integer'],
            'postpaid' => ['required','in:0,1', 'integer'],
            'periodicity' => ['nullable', 'required_if:consumable,true', 'integer'],
            'periodicity_type' => ['nullable', 'required_if:consumable,true', new Enum(Period::class)], // Örneğin, gün, hafta, ay
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
            'status.required' => __('admin/features/features.status.required'),

            /**
             * Title Messages
             */
            'title.required' => __('admin/features/features.title.required'),
            'title.string' => __('admin/features/features.title.string'),
            'title.max' => __('admin/features/features.title.max'),
            'title.unique' => __('admin/setting/contract.title.unique'),

            /**
             * Name Messages
             */
            'name.unique' => __('admin/setting/contract.name.unique'),

            /**
             * Consumable Messages
             */
            'consumable.required' => __('admin/features/features.consumable.required'),
            'consumable.in' => __('admin/features/features.consumable.in'),
            'consumable.integer' => __('admin/features/features.consumable.integer'),

            /**
             * Quota Messages
             */
            'quota.required' => __('admin/features/features.quota.required'),
            'quota.in' => __('admin/features/features.quota.in'),
            'quota.integer' => __('admin/features/features.quota.integer'),

            /**
             * Postpaid Messages
             */
            'postpaid.required' => __('admin/features/features.postpaid.required'),
            'postpaid.in' => __('admin/features/features.postpaid.in'),
            'postpaid.integer' => __('admin/features/features.postpaid.integer'),

            /**
             * Period Count Messages
             */
            'periodicity.required_if' => __('admin/features/features.periodicity.required_if'),
            'periodicity.integer' => __('admin/features/features.periodicity.integer'),

            /**
             * Period Type Messages
             */
            'periodicity_type.required_if' => __('admin/features/features.periodicity_type.required_if'),
            'periodicity_type.in' => __('admin/features/features.periodicity_type.in'),
        ];
    }

}
