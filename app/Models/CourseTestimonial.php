<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseTestimonial extends Model
{
    protected $table = 'course_testimonials';

    protected $fillable = [
        'user_id',
        'description',
        'review',
        'course_id'
    ];

    public function Course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
