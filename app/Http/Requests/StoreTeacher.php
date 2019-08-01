<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacher extends FormRequest
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
            'username' => 'required|min:6|max:50|unique:teachers,username',
            'password' => 'required',
            'password_repeat' => 'required|same:password',
            'full_name' => 'required',
            'email' => 'required|email|unique:teachers,email',
            'personal_contact_number' => 'required|numeric',
            'skype' => 'required|max:30',
            'address' => 'required|max:250',
            'educational_attainment' => 'required|in:0,1',
            'birthday' => [
                'required',
                'date_format' => 'F d, Y'
            ]
        ];
    }
}
