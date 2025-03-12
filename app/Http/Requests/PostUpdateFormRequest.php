<?php

namespace App\Http\Requests;

use App\Enums\StatusType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PostUpdateFormRequest extends FormRequest
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
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'status'  => ['sometimes', 'string',  new Enum(StatusType::class)],
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')->whereNull('deleted_at')],
        ];
    }
}
