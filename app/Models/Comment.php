<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'student_id', // L'utilisateur qui a posté le commentaire
        'document_id',
    ];

    // Relation avec l'utilisateur qui a posté le commentaire
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // commentaire belongs to one doc
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
