<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::all();

        return view('student.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('student.create');
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
                'max:50',
                'string',
                'unique:App\Student,username'
            ],

            'data.password' => [
                'required',
                'string'
            ],

            'data.password_repeat' => [
                'required',
                'same:data.password_repeat',
                'string'
            ],

            'data.full_name' => [
                'required',
                'string',
                'max:100'
            ],

            'data.email' => [
                'required',
                'string',
                'email',
                'unique:App\Student,email'
            ],

            'data.skype' => [
                'present',
                'nullable',
                'string',
                'max:50'
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
                'string',
                'date',
                'date_format:Y-m-d'
            ]
        ]);

        $data = array_filter($request->input('data'), function ($value, $key) {
            return filled($value) && $key !== 'password_repeat';
        }, ARRAY_FILTER_USE_BOTH);

        $data['password'] = Hash::make($data['password']);

        $student = Student::create($data);

        return redirect(route(
            'students.show',
            [ 'student' => $student->id ]
        ))->with('message', 'Successfully created student');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::findOrFail($id);
        $teachers = Teacher::all();

        return view('student.show', compact('student', 'teachers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);

        return view('student.edit', compact('student'));
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
        $student = Student::findOrFail($id);

        $request->validate([
            'data.password' => [
                Rule::requiredIf(function () use ($request) {
                    return filled($request->input('data.password_repeat'));
                }),
                'present',
                'nullable',
                'string'
            ],

            'data.password_repeat' => [
                Rule::requiredIf(function () use ($request) {
                    return filled($request->input('data.password'));
                }),
                'present',
                'nullable',
                'string',
                'same:data.password'
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
                'max:50'
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
        ]);

        $data = array_filter($request->input('data'), function ($value, $key) {
            return filled($value) && $key !== 'password_repeat';
        }, ARRAY_FILTER_USE_BOTH);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        foreach ($data as $column => $value) {
            $student->{$column} = $value;
        }

        $student->save();

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
        $student = Student::findOrFail($id);

        if ($student->classrooms()->count() > 0) {
            return redirect()->back()->withErrors([
                'message' => 'This student have classrooms. Remove the student from the classroom in order to proceed'
            ]);
        }

        $student->delete();

        return redirect(route('students.index'))->with('message', 'Successfully deleted student - good riddance');
    }
}
