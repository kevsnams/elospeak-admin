<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudent extends FormRequest
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
                    'data.username' => [
                        'required',
                        'min:4',
                        'max:50',
                        'unique:students,username'
                    ],

                    'data.password' => [
                        'required'
                    ],

                    'data.password_repeat' => [
                        'required',
                        'same:data.password'
                    ],

                    'data.full_name' => [
                        'required',
                        'max:100'
                    ],

                    'data.email' => [
                        'required',
                        'email',
                        'unique:students,email'
                    ],

                    'data.personal_contact_number' => [
                        'nullable',
                        'numeric'
                    ],

                    'data.skype' => [
                        'required',
                        'max:30'
                    ],

                    'data.birthday' => [
                        'nullable',
                        'date'
                    ],

                    'data.country_code' => [
                        'required_if:type,info',
                        'min:2',
                        'max:2',
                        Rule::exists('countries', 'code_iso3166_a2')
                    ]
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                $rules = [
                    'data.username' => [
                        'required_if:type,credentials',
                        'min:4',
                        'max:50',
                        Rule::unique('students', 'username')->ignore($this->route('student'))
                    ],

                    'data.email' => [
                        'required_if:type,credentials',
                        'email',
                        Rule::unique('students', 'email')->ignore($this->route('student'))
                    ],

                    'data.new_password' => [
                        'required_if:type,password'
                    ],

                    'data.new_password_repeat' => [
                        'required_if:type,password',
                        'same:data.new_password'
                    ],

                    'data.full_name' => [
                        'required_if:type,info',
                        'max:100'
                    ],

                    'data.personal_contact_number' => [
                        'nullable',
                        'numeric'
                    ],

                    'data.skype' => [
                        'required_if:type,info',
                        'max:30'
                    ],

                    'data.birthday' => [
                        'nullable',
                        'date_format:Y-m-d'
                    ],

                    'data.country_code' => [
                        'required_if:type,info',
                        'min:2',
                        'max:2',
                        Rule::exists('countries', 'code_iso3166_a2')
                    ]
                ];

                return $rules;
            }

            default:break;
        }
    }
}
