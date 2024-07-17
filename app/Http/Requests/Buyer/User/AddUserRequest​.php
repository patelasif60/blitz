<?php

namespace App\Http\Requests\Buyer\User;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest​ extends FormRequest
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
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => ['required', 'email'],
            'mobile' => ['required', 'min:9', 'max:16'],
            'designation' => 'required',
            'department' => 'required',
            'role' => 'required'
        ];
    }
}
