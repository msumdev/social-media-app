<?php

namespace App\Http\Requests\Chat\Rooms;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

/**
 * Class CreateChatRoomRequest
 * @package App\Http\Requests\Chat\Rooms
 */
class CreateChatRoomRequest extends BaseRequest
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
            'members' => 'required|array',
            'members.*' => 'required|int|exists:users,id',
        ];
    }
}
