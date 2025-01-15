<?php

namespace App\Http\Requests\Admin\Settings\Agreement;

use Illuminate\Foundation\Http\FormRequest;

class AgreementAcceptRequest extends FormRequest
{
   public function authorize(): bool
   {
       return true;
   }

   public function rules(): array
   {
       return [
           'agreement_id' => ['required', 'uuid', 'exists:agreements,id'],
           'version_id' => ['required', 'uuid', 'exists:agreement_versions,id']
       ];
   }

   public function messages(): array
   {
       return [
           'agreement_id.required' => 'Sözleşme bilgisi gereklidir.',
           'agreement_id.uuid' => 'Geçersiz sözleşme bilgisi.',
           'agreement_id.exists' => 'Sözleşme bulunamadı.',

           'version_id.required' => 'Sözleşme versiyonu gereklidir.',
           'version_id.uuid' => 'Geçersiz versiyon bilgisi.',
           'version_id.exists' => 'Sözleşme versiyonu bulunamadı.'
       ];
   }
}
