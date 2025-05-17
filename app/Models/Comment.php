<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id', // L'utilisateur qui a posté le commentaire
        'document_id',
    ];

    // Relation avec l'utilisateur qui a posté le commentaire
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le document auquel ce commentaire appartient
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    // récupérer tous les commentaires d'un document 
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
