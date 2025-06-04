<?php

namespace App\Http\Requests\User\Posts\Comments;

use App\Http\Requests\BaseRequest;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;

/**
 * Class TogglePostLikeRequest
 */
class CreatePostCommentReactionRequest extends BaseRequest
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
            'id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (! Post::where('_id', $value)->exists()) {
                        $fail('The comment does not exist.');
                    }
                },
            ],
            'comment_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (! PostComment::where('_id', $value)->exists()) {
                        $fail('The comment does not exist.');
                    }
                },
            ],
            'reaction' => 'required|string',
            'reaction_unicode' => 'required|string',
        ];
    }
}
