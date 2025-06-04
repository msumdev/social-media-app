<?php

namespace App\Http\Requests\User\Filters;

use App\Http\Requests\BaseRequest;

/**
 * Class UpdateFiltersRequest
 */
class UpdateFiltersRequest extends BaseRequest
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
            'age_from' => 'required|integer|min:10|max:120',
            'age_to' => 'required|integer|min:10|max:120',
            'sexes' => 'sometimes|array',
            'sexes.*' => 'sometimes|numeric|exists:sexes,id',
            'genders' => 'sometimes|array',
            'genders.*' => 'sometimes|numeric|exists:genders,id',
            'countries' => 'sometimes|array',
            'countries.*' => 'sometimes|numeric|exists:countries,id',
            'city' => 'nullable|numeric|exists:cities,id',
            'online' => 'sometimes|boolean',
            'keywords' => 'sometimes|array',
            'interests' => 'sometimes|array',
            'interests.*' => 'sometimes|numeric|exists:interests,id',
            'username' => 'nullable|string',
        ];
    }
}
