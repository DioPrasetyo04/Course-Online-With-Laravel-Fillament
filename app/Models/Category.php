<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use SoftDeletes;
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    // method mutator untuk mendeskripsikan masing-masing atribute agar sesuai dengan value yang diinginkan
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
