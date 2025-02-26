<?php

namespace App\Http\Requests\Chat\Rooms;

use App\Http\Requests\BaseRequest;
use App\Models\Room\Room;

/**
 * Class DeleteChatRoomRequest
 * @package App\Http\Requests\Chat\Rooms
 */
class DeleteChatRoomRequest extends BaseRequest
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
        ];
    }
}
