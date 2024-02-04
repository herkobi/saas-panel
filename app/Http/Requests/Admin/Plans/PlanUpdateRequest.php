<?php

namespace App\Http\Requests\Admin\Plans;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PlanUpdateRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', Rule::unique('plans', 'title')->ignore($this->plan->id, 'id')],
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
            'status.integer' => __('admin/plans/plan.status.integer'),

            /**
             * Title Messages
             */
            'title.required' => __('admin/plans/plan.title.required'),
            'title.string' => __('admin/plans/plan.title.string'),
            'title.max:255' => __('admin/plans/plan.title.max'),
            'title.unique' => __('admin/plans/plan.title.unique'),
        ];
    }

}
