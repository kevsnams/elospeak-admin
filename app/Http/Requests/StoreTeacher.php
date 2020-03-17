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
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'username' => 'required|min:6|max:50|unique:teachers,username',
                    'password' => 'required',
                    'password_repeat' => 'required|same:password',
                    'full_name' => 'required|min:1|max:100',
                    'email' => 'required|email|unique:teachers,email',
                    'personal_contact_number' => 'numeric',
                    'skype' => 'required|max:30',
                    'salary' => 'required|numeric',
                    'address' => 'max:250',
                    'educational_attainment' => 'required|in:0,1',
                    'birthday' => [
                        'date_format:d F Y'
                    ]
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'username' => 'required|min:6|max:50|unique:teachers,username,'. $this->input('username') .',username',
                    'password' => 'sometimes|present',
                    'password_repeat' => 'sometimes|present|same:password',
                    'full_name' => 'required',
                    'email' => 'required|email|unique:teachers,email,'. $this->input('email') .',email',
                    'personal_contact_number' => 'required|numeric',
                    'skype' => 'required|max:30',
                    'address' => 'required|max:250',
                    'educational_attainment' => 'required|in:0,1',
                    'birthday' => [
                        'date_format:d F Y'
                    ]
                ];
            }
            default:break;
        }
    }
}
