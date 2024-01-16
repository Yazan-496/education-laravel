<?php

namespace App\Http\Validation;

use Illuminate\Support\Facades\Validator;

class LoginValidation
{
    public static function rules()
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }
    public static function messages()
    {
        return [
            'email.required' => 'Please enter your email address.',
            'password.required' => 'Please enter a password.',
        ];
    }
    public static function validate(array $data)
    {
        return Validator::make($data, self::rules(), self::messages());
    }
}
