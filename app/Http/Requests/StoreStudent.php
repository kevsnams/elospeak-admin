<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    public function withValidator($validator)
    {
        /*
        $validator->after(function ($validator) {
            if ($this->checkScheduleDaysValue()) {
                $validator->errors()->add('schedule_days', 'One of the preferred days has an incorrect value');
            }
        });
        */
    }

    private function checkScheduleDaysValue()
    {
        $allowed = ['M', 'T', 'W', 'Th', 'F', 'S'];
        $hasError = false;
        foreach ($this->input('schedule_days') as $day) {
            if (!in_array($day, $allowed)) {
                $hasError = true;
            }
        }

        return $hasError;
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
                    'username' => 'required|min:4|max:50|unique:students,username',
                    'password' => 'required',
                    'password_repeat' => 'required|same:password',
                    'full_name' => 'required',
                    'email' => 'required|email|unique:students,email',
                    'personal_contact_number' => 'numeric',
                    'skype' => 'required|max:30',
                    'birthday' => [
                        'date_format:d F Y'
                    ],
                    'schedule_days' => 'array',
                    'schedule_start_time' => [
                        'regex:/[0-9][0-9]\:[0-9][0-9]/'
                    ],
                    'schedule_start_date' => [
                        'date_format:d F Y'
                    ]
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'username' => 'required|min:4|max:50|unique:students,username,'. $this->input('username') .',username',
                    'password' => 'sometimes|present',
                    'password_repeat' => 'sometimes|present|same:password',
                    'full_name' => 'required',
                    'email' => 'required|email|unique:students,email,'. $this->input('email') .',email',
                    'personal_contact_number' => 'required|numeric',
                    'skype' => 'required|max:30',
                    'birthday' => [
                        'required',
                        'date_format:d F Y'
                    ],
                    'schedule_days' => 'required|array',
                    'schedule_start_time' => [
                        'required',
                        'regex:/[0-9][0-9]\:[0-9][0-9]/'
                    ],
                    'schedule_start_date' => [
                        'required',
                        'date_format:d F Y'
                    ]
                ];
            }
            default:break;
        }
    }
}
