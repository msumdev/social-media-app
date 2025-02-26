<?php

namespace App\Http\Requests\Chat\Friends;

use App\Http\Requests\BaseRequest;

/**
 * Class ChatFriendListIndexRequest
 * @package App\Http\Requests\Chat\Friends
 */
class ChatFriendListIndexRequest extends BaseRequest
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
            'search' => 'nullable|string',
            'exclude_existing' => 'boolean|nullable',
            'room_id' => 'integer|nullable|exists:rooms,id',
        ];
    }
}
