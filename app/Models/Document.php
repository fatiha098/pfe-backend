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
        'user_id',
        'is_validated',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
