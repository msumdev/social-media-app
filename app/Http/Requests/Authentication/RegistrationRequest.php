<?php

namespace App\Http\Requests\Authentication;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegistrationRequest
 */
class RegistrationRequest extends FormRequest
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
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'date_of_birth' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $minimumAge = 12;
                    $maximumAge = 120;
                    $birthDate = Carbon::parse($value);
                    $age = $birthDate->age;

                    if ($age < $minimumAge) {
                        $fail('You must be at least '.$minimumAge.' years old to register.');
                    }

                    if ($age > $maximumAge) {
                        $fail('You must be at most '.$maximumAge.' years old to register.');
                    }
                },
            ],
            'first_name' => 'required|string',
            'last_name' => 'required|string',
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
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'gender_id' => 'required|exists:genders,id',
            'sexuality_id' => 'required|exists:sexuality_types,id',
            'interests' => 'required|array|exists:interest_types,id',
            'sex_id' => 'required|exists:sexes,id',
        ];
    }

    public function prepareForValidation(): void
    {
        if ($this->has('date_of_birth')) {
            $this->merge([
                'date_of_birth' => Carbon::parse($this->date_of_birth)->format('Y-m-d'),
            ]);
        }
    }
}
