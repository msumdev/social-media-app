<?php

namespace App\Http\Requests\User\ReportUser;

use App\Http\Requests\BaseRequest;

/**
 * Class GetUserReportsRequest
 * @package App\Http\Requests\User\ReportUser
 */
class GetUserReportsRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo('view-user-reports');
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
