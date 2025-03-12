<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes; // Add SoftDeletes trait
    protected $fillable = ['comment', 'status', 'user_id', 'post_id'];
    protected $table = 'comments';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
