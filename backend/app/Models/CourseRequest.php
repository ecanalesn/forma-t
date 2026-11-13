<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRequest extends Model
{
    protected $table = 'course_requests';
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'user_id',
        'course_id',
        'request_date',
        'status',
        'allowed_access',
        'is_read',
    ];

    // Devuelve true/false en el JSON con cualquier base de datos
    // (SQLite y MySQL guardan 0/1; PostgreSQL usa booleanos nativos)
    protected $casts = [
        'allowed_access' => 'boolean',
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}
