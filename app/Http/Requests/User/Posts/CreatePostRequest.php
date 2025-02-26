<?php

namespace App\Http\Requests\User\Posts;

use App\Http\Requests\BaseRequest;

/**
 * Class CreatePostRequest
 * @package App\Http\Requests\User\Posts
 */
class CreatePostRequest extends BaseRequest
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
            'content' => 'required|string',
            'mentions' => 'sometimes|array',
            'hashtags' => 'sometimes|array',
            'images' => 'sometimes|array',
            'images.*.data' => 'required|image|mimes:jpeg,png,jpg,gif|max:51200',
            'audios' => 'sometimes|array',
            'audios.*.data' => 'required|file|mimes:mp3,wav,ogg|max:51200',
        ];
    }
}
