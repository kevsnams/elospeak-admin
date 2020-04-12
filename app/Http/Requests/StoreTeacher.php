<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Teacher;

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
                    'data.username' => [
                        'required',
                        'min:6',
                        'max:50',
                        'unique:teachers,username'
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
                        'min:1',
                        'max:100'
                    ],

                    'data.email' => [
                        'required',
                        'email',
                        'unique:teachers,email'
                    ],

                    'data.personal_contact_number' => [
                        'sometimes',
                        'required',
                        'numeric'
                    ],

                    'data.skype' => [
                        'required',
                        'max:30'
                    ],

                    'data.salary' => [
                        'required',
                        'numeric'
                    ],

                    'data.salary_weekend' => [
                        'required',
                        'numeric'
                    ],

                    'data.address' => [
                        'sometimes',
                        'required',
                        'max:250'
                    ],

                    'data.educational_attainment' => [
                        'required',
                        'in:0,1'
                    ],

                    'data.birthday' => [
                        'sometimes',
                        'required',
                        'date_format:Y-m-d'
                    ]
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'data.username' => [
                        'sometimes',
                        'required',
                        'min:6',
                        'max:50',
                        Rule::unique('teachers', 'username')->ignore($this->route('teacher'))
                    ],

                    'data.password' => [
                        'sometimes',
                        'required'
                    ],

                    'data.password_repeat' => [
                        'sometimes',
                        'required',
                        'same:data.password'
                    ],

                    'data.full_name' => [
                        'sometimes',
                        'required',
                        'max:100'
                    ],

                    'data.email' => [
                        'sometimes',
                        'required',
                        'email',
                        Rule::unique('teachers', 'email')->ignore($this->route('teacher'))
                    ],

                    'data.personal_contact_number' => [
                        'sometimes',
                        'nullable',
                        'numeric'
                    ],

                    'data.skype' => [
                        'sometimes',
                        'required',
                        'max:30'
                    ],

                    'data.address' => [
                        'sometimes',
                        'present',
                        'max:250'
                    ],

                    'data.educational_attainment' => [
                        'sometimes',
                        'required',
                        'in:'. Teacher::educationalAttainments()->map(function ($value) {
                            return $value[0];
                        })->join(','),
                    ],

                    'data.birthday' => [
                        'sometimes',
                        'nullable',
                        'date_format:Y-m-d'
                    ],

                    'data.salary' => [
                        'required',
                        'sometimes',
                        'numeric'
                    ],

                    'data.salary_weekend' => [
                        'required',
                        'sometimes',
                        'numeric'
                    ],
                ];
            }
            default:break;
        }
    }
}
