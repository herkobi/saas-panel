<?php

namespace App\Rules;

use App\Traits\AuthUser;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueTenant implements Rule
{
    use AuthUser;

    private string $table;
    private string $column;
    private ?string $ignore = null;

    public function __construct(string $table, string $column)
    {
        $this->table = $table;
        $this->column = $column;
        $this->initializeAuthUser();
    }

    public function passes($attribute, $value): bool
    {
        $query = DB::table($this->table)
            ->where($this->column, $value)
            ->where('tenant_id', $this->user->tenant_id);

        if ($this->ignore) {
            $query->where('id', '!=', $this->ignore);
        }

        return !$query->exists();
    }

    public function message(): string
    {
        return __('Girmiş olduğunuz değerle aynı isimde kayıt bulunmaktadır');
    }

    public function ignore($id): self
    {
        $this->ignore = $id;
        return $this;
    }
}
