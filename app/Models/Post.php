<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = ['title', 'content', 'status', 'user_id'];
    protected $dates = ['deleted_at'];

    protected $table = 'posts';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
