<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class Registration extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'username' => 'required|alpha_num|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3',
            'terms' => 'required',
            'gender' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'terms.required' => trans('message.terms_validation_message')
        ];
    }
}
