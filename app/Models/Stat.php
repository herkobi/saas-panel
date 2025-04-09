<?php

namespace App\Models;

use App\Enums\StatType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'stats';

    protected $fillable = [
        'link_id',
        'name',
        'value',
        'count',
        'date'
    ];

    protected function casts(): array
    {
        return [
            'name' => StatType::class,
            'count' => 'integer',
            'date' => 'date'
        ];
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }
}
