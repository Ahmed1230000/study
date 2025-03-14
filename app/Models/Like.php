<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['liked', 'likeable_type', 'likeable_id'];

    public function likeable()
    {
        return $this->morphTo();
    }
}
