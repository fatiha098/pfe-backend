<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'file_path',
        'description',
        'student_id',
        'is_validated',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
