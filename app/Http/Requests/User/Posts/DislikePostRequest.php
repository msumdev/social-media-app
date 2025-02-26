<?php

namespace App\Http\Requests\User\Posts;

use App\Http\Requests\BaseRequest;

/**
 * Class DislikePostRequest
 * @package App\Http\Requests\User\Posts
 */
class DislikePostRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:posts,id'
        ];
    }
}
