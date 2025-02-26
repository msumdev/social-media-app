<?php

namespace App\Http\Requests\Chat\Message\Report;

use App\Http\Requests\BaseRequest;
use App\Models\Room\RoomMessage;

/**
 * Class CreateMessageReportRequest
 * @package App\Http\Requests\Chat\Message\Report
 */
class CreateMessageReportRequest extends BaseRequest
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
            'room_id' => 'required|string|exists:rooms,id',
            'message_id' => [
                'required',
                'string',
                function($attribute, $value, $fail) {
                    $message = RoomMessage::find($value);

                    if (!$message) {
                        $fail('The message does not exist.');
                        return;
                    }

                    $messageReport = $message->reports()->where('user_id', auth()->id())->first();

                    if ($message->user->id === auth()->id()) {
                        $fail('You cannot report your own message.');
                        return;
                    }

                    if ($messageReport) {
                        $fail('You have already reported this message.');
                        return;
                    }
                }
            ],
            'reasons' => 'required|array|exists:report_reasons,id',
            'description' => 'nullable|string|max:255',
        ];
    }
}
