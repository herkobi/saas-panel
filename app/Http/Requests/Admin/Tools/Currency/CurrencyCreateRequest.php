<?php

namespace App\Http\Requests\Admin\Tools\Currency;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Status;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CurrencyCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(Status::class)],
            'name' => ['required', 'string', 'max:100', Rule::unique('currencies', 'name')],
            'symbol' => ['required', 'string', 'max:10'],
            'symbol_position' => ['required', 'string', 'in:left,right,left_space,right_space'],
            'thousands_separator' => ['required', 'string', 'size:1'],
            'decimal_separator' => ['required', 'string', 'size:1'],
            'decimal_digits' => ['required', 'integer', 'min:0', 'max:4'],
            'iso_code' => ['required', 'string', 'size:3', Rule::unique('currencies', 'iso_code'), 'alpha', 'uppercase'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Durum alanı zorunludur.',
            'status.enum' => 'Durum geçersiz.',

            'name.required' => 'Para birimi adı alanı zorunludur.',
            'name.string' => 'Para birimi adı metin olmalıdır.',
            'name.max' => 'Para birimi adı en fazla :max karakter olabilir.',
            'name.unique' => 'Bu para birimi adı zaten kayıtlı.',

            'symbol.required' => 'Sembol alanı zorunludur.',
            'symbol.string' => 'Sembol metin olmalıdır.',
            'symbol.max' => 'Sembol en fazla :max karakter olabilir.',

            'symbol_position.required' => 'Sembol konumu alanı zorunludur.',
            'symbol_position.string' => 'Sembol konumu metin olmalıdır.',
            'symbol_position.in' => 'Sembol konumu geçersiz.',

            'thousands_separator.required' => 'Binlik ayırıcı alanı zorunludur.',
            'thousands_separator.string' => 'Binlik ayırıcı metin olmalıdır.',
            'thousands_separator.size' => 'Binlik ayırıcı tek karakter olmalıdır.',

            'decimal_separator.required' => 'Ondalık ayırıcı alanı zorunludur.',
            'decimal_separator.string' => 'Ondalık ayırıcı metin olmalıdır.',
            'decimal_separator.size' => 'Ondalık ayırıcı tek karakter olmalıdır.',

            'decimal_digits.required' => 'Ondalık basamak sayısı alanı zorunludur.',
            'decimal_digits.integer' => 'Ondalık basamak sayısı tam sayı olmalıdır.',
            'decimal_digits.min' => 'Ondalık basamak sayısı en az :min olabilir.',
            'decimal_digits.max' => 'Ondalık basamak sayısı en fazla :max olabilir.',

            'iso_code.required' => 'ISO kodu alanı zorunludur.',
            'iso_code.string' => 'ISO kodu metin olmalıdır.',
            'iso_code.size' => 'ISO kodu :size karakter olmalıdır.',
            'iso_code.unique' => 'Bu ISO kodu zaten kayıtlı.',
            'iso_code.alpha' => 'ISO kodu sadece harflerden oluşmalıdır.',
            'iso_code.uppercase' => 'ISO kodu büyük harflerden oluşmalıdır.',
        ];
    }
}
