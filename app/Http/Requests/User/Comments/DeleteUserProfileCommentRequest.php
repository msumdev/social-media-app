<?php

namespace App\Http\Requests\User\Comments;

use App\Http\Requests\BaseRequest;
use App\Models\User\ProfileComment;

/**
 * Class DeleteUserProfileCommentRequest
 * @package App\Http\Requests\User\Comments
 */
class DeleteUserProfileCommentRequest extends BaseRequest
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
                function($attribute, $value, $fail) {
                    if (!ProfileComment::where('_id', $value)->exists()) {
                        $fail('The comment does not exist');
                    }
                }
            ]
        ];
    }
}
