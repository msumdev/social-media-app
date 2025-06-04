<?php

namespace App\Http\Requests\Chat\Rooms;

use App\Http\Requests\BaseRequest;

/**
 * Class GetChatRoomsRequest
 */
class GetChatRoomsRequest extends BaseRequest
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
            'type' => 'sometimes|string|in:direct,group,archived',
        ];
    }
}
