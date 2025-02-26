<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

/**
 * Class BlockUserRequest
 * @package App\Http\Requests\User
 */
class BlockUserRequest extends BaseRequest
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
            'id' => 'required|exists:users,id',
            'reason' => 'nullable|string|max:255',
        ];
    }
}
