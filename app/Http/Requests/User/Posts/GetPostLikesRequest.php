<?php

namespace App\Http\Requests\User\Posts;

use App\Http\Requests\BaseRequest;
use App\Models\Posts\Post;

/**
 * Class GetPostLikesRequest
 */
class GetPostLikesRequest extends BaseRequest
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
                        $fail('The post does not exist.');
                    }
                },
            ],
        ];
    }
}
