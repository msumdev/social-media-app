<?php

namespace App\Http\Requests\User\Comments;

use App\Http\Requests\BaseRequest;

/**
 * Class CreateUserProfileCommentRequest
 * @package App\Http\Requests\User\Comments
 */
class CreateUserProfileCommentRequest extends BaseRequest
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
            'username' => 'required|exists:users,username',
            'content' => 'required|string|max:2048',
            'audios' => 'sometimes|array',
            'audios.*.data' => 'required|file|mimes:mp3,wav,ogg|max:51200',
        ];
    }
}
