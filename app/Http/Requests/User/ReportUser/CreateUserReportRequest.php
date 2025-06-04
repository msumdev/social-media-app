<?php

namespace App\Http\Requests\User\ReportUser;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

/**
 * Class CreateUserReportRequest
 */
class CreateUserReportRequest extends BaseRequest
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
                'exists:users,id',
                Rule::notIn([auth()->id()]),
            ],
            'reason' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'id.not_in' => 'You cannot report yourself',
        ];
    }
}
