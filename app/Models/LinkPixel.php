<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LinkPixel extends Pivot
{
    use HasFactory;

    protected $table = 'link_pixel';

    public $timestamps = false;

    protected $fillable = [
        'link_id',
        'pixel_id'
    ];
}
