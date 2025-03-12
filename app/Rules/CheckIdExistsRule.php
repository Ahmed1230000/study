<?php

namespace App\Rules;

use App\Models\Comment;
use App\Models\Post;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckIdExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $existsInPosts = Post::where('id', $value)->exists();
        $existsInComments = Comment::where('id', $value)->exists();

        // If the ID doesn't exist in either table, call the $fail callback
        if (!($existsInPosts || $existsInComments)) {
            $fail("The $attribute doesn't exist.");
        }
    }
}
