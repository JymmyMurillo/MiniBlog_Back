<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content'
    ];

    // Relación con el usuario autor del post
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con los comentarios del post
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
