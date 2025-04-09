<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Sluggable
{
    public function generateSlug($string)
    {
        $string = $this->turkishToEnglish($string);
        $slug = Str::slug($string ?? '', '-');
        $count = 1;

        while ($this->slugExists($slug)) {
            $slug = Str::slug($string . '-' . $count ?? '', '-');
            $count++;
        }

        return $slug;
    }

    protected function slugExists($slug)
    {
        return static::where('slug', $slug)->exists();
    }

    protected function turkishToEnglish($str)
    {
        return str_replace(
            ['ğ', 'Ğ', 'ü', 'Ü', 'ş', 'Ş', 'ı', 'İ', 'ö', 'Ö', 'ç', 'Ç'],
            ['g', 'G', 'u', 'U', 's', 'S', 'i', 'I', 'o', 'O', 'c', 'C'],
            $str
        );
    }
}
