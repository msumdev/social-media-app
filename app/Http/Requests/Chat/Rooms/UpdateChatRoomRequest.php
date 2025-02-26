<?php

namespace App\Http\Requests\Chat\Rooms;

use App\Http\Requests\BaseRequest;
use App\Models\Room\Room;

/**
 * Class UpdateChatRoomRequest
 * @package App\Http\Requests\Chat\Rooms
 */
class UpdateChatRoomRequest extends BaseRequest
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
                'integer',
                'exists:rooms,id',
                function ($attribute, $value, $fail) {
                    $room = Room::find($value);

                    if (!$room) {
                        $fail('Room not found');
                    }

                    if (!$room->members->contains('id', auth()->id())) {
                        $fail('You are not a member of this room');
                    }
                }
            ],
            'name' => 'sometimes|string|max:32',
            'archive' => 'sometimes|boolean',
            'users' => 'sometimes|array',
            'users.*' => 'integer|exists:users,id',
            'purge' => 'sometimes|boolean',
            'type' => 'sometimes|string|in:' . implode(',', ['add', 'remove']),
        ];
    }
}
