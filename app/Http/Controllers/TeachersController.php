<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;

use App\Http\Requests\StoreTeacher as StoreTeacherRequest;

use Hash;

class TeachersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::orderBy('full_name', 'ASC')->get();

        return view('teachers.index', [
            'teachers' => $teachers,
            'educationalAttainments' => Teacher::getEducationalAttainmentValues()
        ]);
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
    public function store(StoreTeacherRequest $request)
    {
        $input = $request->validated();

        $password = Hash::make($input['password']);

        $teacher = new Teacher();
        $teacher->username = $input['username'];
        $teacher->password = $password;
        $teacher->full_name = ucwords($input['full_name']);
        $teacher->email = $input['email'];
        $teacher->personal_contact_number = $input['personal_contact_number'];
        $teacher->skype = $input['skype'];
        $teacher->address = $input['address'];
        $teacher->educational_attainment = $input['educational_attainment'];
        $teacher->birthday = date('Y-m-d', strtotime($input['birthday']));

        $teacher->save();

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
