<?php

namespace App\Http\Controllers;

use App\SignupApplication;
use App\Student;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SignupApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = SignupApplication::latest()->get();

        return view('signup-application.index', compact('applications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $application = SignupApplication::findOrfail($id);

        $genUsername = 'student_'. $application->id;
        $genPassword = Str::lower(Str::random(8));

        return view('signup-application.show', compact('application', 'genUsername', 'genPassword'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function accept(Request $request, $id)
    {
        $application = SignupApplication::findOrFail($id);

        $validator = Validator::make([
            'username' => $request->username,
            'password' => $request->password,
            'full_name' => $application->json->full_name,
            'email' => $application->json->email,
            'skype' => $application->json->skype,
            'personal_contact_number' => $application->json->contact_number ?: null,
            'birthday' => $application->json->birthday ?: null
        ], [
            'username' => [
                'required',
                'max:50',
                'string',
                'regex:/^[a-zA-Z0-9\.\_]*$/',
                'unique:App\Student,username'
            ],

            'password' => [
                'required',
                'string'
            ],

            'full_name' => [
                'required',
                'string',
                'max:100'
            ],

            'email' => [
                'required',
                'string',
                'email',
                'unique:App\Student,email'
            ],

            'skype' => [
                'required',
                'string',
                'max:50'
            ],

            'personal_contact_number' => [
                'nullable',
                'string',
                'max:20'
            ],

            'birthday' => [
                'nullable',
                'string',
                'date',
                'date_format:Y-m-d'
            ]
        ]);

        if ($validator->fails()) {
            return redirect(route('applications.show', [ 'application' => $application->id ]))->withErrors($validator);
        }

        $student = new Student();
        $student->username = $request->username;
        $student->password = Hash::make($request->password);
        $student->full_name = $application->json->full_name;
        $student->email = $application->json->email;
        $student->skype = $application->json->skype;
        $student->personal_contact_number = $application->json->contact_number ?: null;
        $student->birthday = $application->json->birthday ?: null;
        $student->save();

        $application->status = SignupApplication::STATUS_ACCEPTED;
        $application->student_id = $student->id;
        $application->save();

        return redirect(route('applications.index'))->with('message', 'Successfully accepted student application. View <a href="'. route('students.show', [ 'student' => $student->id ]) .'" class="alert-link">student account</a>');
    }

    public function deny(Request $request, $id)
    {
        $application = SignupApplication::findOrFail($id);
        $application->status = SignupApplication::STATUS_DENIED;
        $application->save();

        return redirect(route('applications.index'))->with('message', 'Successfully denied student application');
    }
}
