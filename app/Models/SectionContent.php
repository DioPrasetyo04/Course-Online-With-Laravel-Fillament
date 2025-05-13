<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SectionContent extends Model
{
    use SoftDeletes;
    protected $table = 'section_contents';

    protected $fillable = [
        'name',
        'course_section_id',
        'content'
    ];

    public function courseSection(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }
}
