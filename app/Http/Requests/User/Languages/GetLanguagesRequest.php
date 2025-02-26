<?php

namespace App\Http\Requests\User\Languages;

use App\Http\Requests\BaseRequest;

/**
 * Class GetLanguagesRequest
 * @package App\Http\Requests\User\Languages
 */
class GetLanguagesRequest extends BaseRequest
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
        return [];
    }
}
