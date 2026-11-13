<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $primaryKey = 'course_id';

    protected $fillable = [
        'course_name',
        'description',
        'category',
        'image',
        'status',
        'content',
        'video_link',
    ];

    public function courseRequests()
    {
        return $this->hasMany(CourseRequest::class, 'course_id', 'course_id');
    }
}
