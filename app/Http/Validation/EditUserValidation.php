<?php

namespace App\Http\Validation;

use Illuminate\Support\Facades\Validator;

class EditUserValidation
{
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer',
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'name field is required',
            'email.required' => 'email field is required',
            'email.email' => 'email field is required',
            'password.required' => 'role_id field is required.',
            'password.min' => 'The password must be at least 6 characters.',
            'role_id.required' => 'role_id field is required',
            'role_id.integer' => 'role_id field must be number',
        ];
    }

    public static function validate($data)
    {
        $validator = Validator::make($data, self::rules(), self::messages());

        return $validator->errors()->all();

    }
}
