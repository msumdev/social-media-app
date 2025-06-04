<?php

namespace App\Http\Requests\Authentication;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ResetPasswordRequest
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

    public function prepareForValidation()
    {
        if ($this->route('token')) {
            $this->merge([
                'token' => $this->route('token'),
            ]);
        }
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
                        $fail('The '.$attribute.' must be at least '.$passwordLength.' characters.');
                    }

                    if (! preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬\-!\[\]:;"\/\\\`\.,<>?]/', $value)) {
                        $fail('The '.$attribute.' must contain at least one special character.');
                    }

                    if (! preg_match('/[0-9]/', $value)) {
                        $fail('The '.$attribute.' must contain at least one number.');
                    }

                    if (! preg_match('/[A-Z]/', $value)) {
                        $fail('The '.$attribute.' must contain at least one capital letter.');
                    }

                    if (! preg_match('/[a-z]/', $value)) {
                        $fail('The '.$attribute.' must contain at least one lowercase letter.');
                    }
                },
            ],
            'confirm_password' => 'required|string|same:password',
            'token' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $user = User::where('password_reset_token', $value)->first();

                    if (! $user) {
                        $fail('This token doesn\'t exist');
                    }

                    $expired = Carbon::parse($user->password_reset_token_expires_at)->addHours(2)->lt(now());

                    if ($user->password_reset_token_expires_at && $expired) {
                        $fail('The password reset token has expired. Please request a new one.');
                    }
                },
            ],
        ];
    }
}
