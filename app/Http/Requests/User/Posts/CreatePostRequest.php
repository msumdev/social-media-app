<?php

namespace App\Http\Requests\User\Posts;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreatePostRequest
 */
class CreatePostRequest extends FormRequest
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
            'text' => 'required|string',
            'html' => 'required|string',
            'mentions' => 'sometimes|array',
            'hashtags' => 'sometimes|array',
            'images' => 'sometimes|array',
            'images.*.file' => 'required|image|mimes:jpeg,png,jpg,gif|max:51200',
            'audios' => 'sometimes|array',
            'audios.*.data' => 'required|file|mimes:mp3,wav,ogg|max:51200',
        ];
    }
}
