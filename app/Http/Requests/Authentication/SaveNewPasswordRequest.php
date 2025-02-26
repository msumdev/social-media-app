<?php

namespace App\Http\Requests\Authentication;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class ResetPasswordRequest
 * @package App\Http\Requests\Authentication
 */
class SaveNewPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $passwordLength = 8;

                    if (strlen($value) < $passwordLength) {
                        $fail('The ' . $attribute . ' must be at least ' . $passwordLength . ' characters.');
                    }

                    if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬\-!\[\]:;"\/\\\`\.,<>?]/', $value)) {
                        $fail('The ' . $attribute . ' must contain at least one special character.');
                    }

                    if (!preg_match('/[0-9]/', $value)) {
                        $fail('The ' . $attribute . ' must contain at least one number.');
                    }

                    if (!preg_match('/[A-Z]/', $value)) {
                        $fail('The ' . $attribute . ' must contain at least one capital letter.');
                    }

                    if (!preg_match('/[a-z]/', $value)) {
                        $fail('The ' . $attribute . ' must contain at least one lowercase letter.');
                    }
                },
            ],
            'confirm_password' => 'required|string|same:password',
        ];
    }
}
