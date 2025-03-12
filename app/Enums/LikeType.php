<?php

namespace App\Enums;

use App\Models\Comment;
use App\Models\Post;

enum LikeType: string
{
    case COMMENT = 'comment';
    case POST = 'post';

    public static function getLikeableTypes(): array
    {
        return [
            self::COMMENT->value => Comment::class,
            self::POST->value => Post::class,
        ];
    }
}
