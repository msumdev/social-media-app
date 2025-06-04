<?php

namespace App\Http\Requests\User\ReportUser;

use App\Http\Requests\BaseRequest;

/**
 * Class GetUserReportsRequest
 */
class GetReportReasonsRequest extends BaseRequest
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
