<?php

namespace App\Http\Requests\Chat\Message;

use App\Http\Requests\BaseRequest;
use App\Models\Room\Room;

/**
 * Class CreateMessageRequest
 */
class CreateMessageRequest extends BaseRequest
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

                    if (! $room) {
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

                    if (! $member) {
                        $fail('You are not a member of this room');
                    }
                },
            ],
            'content' => 'required|string',
        ];
    }
}
