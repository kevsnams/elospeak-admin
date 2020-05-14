<?php

namespace App\Http\Controllers;

use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::all();

        return view('teacher.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $educationAttainments = [
            Teacher::EDUC_GRADUATE => 'Graduate',
            Teacher::EDUC_UNDERGRADUATE => 'Undergraduate'
        ];

        return view('teacher.create', compact('educationAttainments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'data.username' => [
                'required',
                'string',
                'max:50',
                'unique:App\Teacher,username'
            ],

            'data.email' => [
                'required',
                'string',
                'email',
                'unique:App\Teacher,email'
            ],

            'data.password' => [
                'required',
                'string'
            ],

            'data.password_repeat' => [
                'required',
                'string',
                'same:data.password'
            ],

            'data.full_name' => [
                'required',
                'string',
                'max:100'
            ],

            'data.skype' => [
                'required',
                'string',
                'max:30'
            ],

            'data.personal_contact_number' => [
                'present',
                'nullable',
                'max:20'
            ],

            'data.birthday' => [
                'required',
                'date_format:Y-m-d'
            ],

            'data.address' => [
                'present',
                'nullable',
                'string',
                'max:250'
            ],

            'data.educational_attainment' => [
                'required',
                'numeric',
                'in:'. implode(',', [ Teacher::EDUC_GRADUATE, Teacher::EDUC_UNDERGRADUATE ])
            ],
        ]);

        $data = array_filter($request->input('data'), function ($value, $key) {
            return filled($value) && $key !== 'password_repeat';
        }, ARRAY_FILTER_USE_BOTH);

        $data['password'] = Hash::make($data['password']);

        $teacher = Teacher::create($data);

        return redirect(route(
            'teachers.show',
            [ 'teacher' => $teacher->id ]
        ))->with('message', 'Successfully created teacher');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);

        return view('teacher.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);

        $educationAttainments = [
            Teacher::EDUC_GRADUATE => 'Graduate',
            Teacher::EDUC_UNDERGRADUATE => 'Undergraduate'
        ];

        return view('teacher.edit', compact('teacher', 'educationAttainments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $request->validate([
            'data.password' => [
                Rule::requiredIf(filled($request->input('data.password_repeat'))),
                'present',
                'nullable',
                'string'
            ],

            'data.password_repeat' => [
                Rule::requiredIf(filled($request->input('data.password'))),
                'present',
                'nullable',
                'string'
            ],

            'data.full_name' => [
                'required',
                'string',
                'max:100'
            ],

            'data.skype' => [
                'present',
                'nullable',
                'string',
                'max:30'
            ],

            'data.personal_contact_number' => [
                'present',
                'nullable',
                'string',
                'max:20'
            ],

            'data.birthday' => [
                'present',
                'nullable',
                'date_format:Y-m-d'
            ],

            'data.address' => [
                'present',
                'nullable',
                'string',
                'max:250'
            ],

            'data.educational_attainment' => [
                'present',
                'nullable',
                'numeric',
                'in:'. implode(',', [ Teacher::EDUC_GRADUATE, Teacher::EDUC_UNDERGRADUATE ])
            ],
        ]);

        $data = array_filter($request->input('data'), function ($value, $key) {
            return filled($value) && $key !== 'password_repeat';
        }, ARRAY_FILTER_USE_BOTH);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        foreach ($data as $column => $value) {
            $teacher->{$column} = $value;
        }

        $teacher->save();

        return redirect()->back()->with('message', 'Successfully update student');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);

        if ($teacher->classrooms()->count() > 0) {
            return redirect()->back()->withErrors([
                'message' => 'This teacher have classrooms. Remove the teacher from the classroom in order to proceed'
            ]);
        }

        $teacher->delete();

        return redirect(route('teachers.index'))->with('message', 'Successfully deleted teacher');
    }
}
