<?php

namespace App\Models;

use App\Enums\ContractType;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory, Sluggable;

    protected $table = "contracts";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'title',
        'slug',
        'date',
        'type',
        'content',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'status' => 'boolean',
            'type' => ContractType::class,
        ];
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = $this->generateSlug($value);
    }

    // Tür alanının geçerli değerlerini kontrol eden bir metod
    public function setTypeAttribute($value)
    {
        if (!in_array($value, ContractType::values())) {
            throw new \InvalidArgumentException("Geçersiz tür değeri: {$value}");
        }
        $this->attributes['type'] = $value;
    }

}
