<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\WebsiteSetting;
use Auth;

use App\Elospeak\Timeslots;

class StoreEnrollment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::guard()->check()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $timeslots = new Timeslots();
        $classroomTimeSlots = $timeslots->flatten();

        return [
            'student.username' => 'required|min:6|max:50|unique:students,username',
            'student.password' => 'required',
            'student.password_repeat' => 'required|same:student.password',
            'student.full_name' => 'required',
            'student.email' => 'required|email|unique:students,email',
            'student.personal_contact_number' => 'required|numeric',
            'student.skype' => 'required|max:30',
            'student.birthday' => [
                'required',
                'date_format:d F Y'
            ],

            'start_date' => [
                'required',
                'date_format:d F Y'
            ],

            'classroom_schedule_preference.monday.checked' => 'sometimes|required',
            'classroom_schedule_preference.monday.slots.*' => [
                'sometimes',
                'required',
                Rule::in($classroomTimeSlots)
            ],

            'classroom_schedule_preference.tuesday.checked' => 'sometimes|required',
            'classroom_schedule_preference.tuesday.slots.*' => [
                'sometimes',
                'required',
                Rule::in($classroomTimeSlots)
            ],

            'classroom_schedule_preference.wednesday.checked' => 'sometimes|required',
            'classroom_schedule_preference.wednesday.slots.*' => [
                'sometimes',
                'required',
                Rule::in($classroomTimeSlots)
            ],

            'classroom_schedule_preference.thursday.checked' => 'sometimes|required',
            'classroom_schedule_preference.thursday.slots.*' => [
                'sometimes',
                'required',
                Rule::in($classroomTimeSlots)
            ],

            'classroom_schedule_preference.friday.checked' => 'sometimes|required',
            'classroom_schedule_preference.friday.slots.*' => [
                'sometimes',
                'required',
                Rule::in($classroomTimeSlots)
            ],

            'classroom_schedule_preference.saturday.checked' => 'sometimes|required',
            'classroom_schedule_preference.saturday.slots.*' => [
                'sometimes',
                'required',
                Rule::in($classroomTimeSlots)
            ],

            'classroom_schedule_preference.sunday.checked' => 'sometimes|required',
            'classroom_schedule_preference.sunday.slots.*' => [
                'sometimes',
                'required',
                Rule::in($classroomTimeSlots)
            ]
        ];
    }
}
