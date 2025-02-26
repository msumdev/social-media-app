<?php

namespace App\Http\Requests\Chat\Message;

use App\Http\Requests\BaseRequest;
use App\Models\Room\Room;
use App\Models\Room\RoomMessage;

/**
 * Class CreateMessageRequest
 * @package App\Http\Requests\Chat\Message
 */
class DeleteMessageRequest extends BaseRequest
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

                    $member = false;

                    if ($room->recipient_id === auth()->id()) {
                        $member = true;
                    }

                    if ($room->author_id === auth()->id()) {
                        $member = true;
                    }

                    if ($room->members->contains('user_id', auth()->id())) {
                        $member = true;
                    }

                    if (!$member) {
                        $fail('You are not a member of this room');
                    }
                }
            ],
            'message_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $message = RoomMessage::find($value);

                    if ($message === null) {
                        $fail('Message not found');

                        return;
                    }

                    if ($message->author_id != auth()->id()) {
                        $fail('You are not the author of this message');
                    }
                }
            ],
        ];
    }
}
