<?php

namespace App\Http\Requests;

use App\Enums\LikeType;
use App\Rules\CheckIdExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class LikeStoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'liked'          => ['required', 'boolean', 'in:1,0'],
            'likeable_type'  => ['required', 'string', new Enum(LikeType::class)],
            'likeable_id'    => ['required', 'integer',new CheckIdExistsRule]
        ];
    }
}
