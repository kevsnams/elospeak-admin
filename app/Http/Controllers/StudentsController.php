<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;

use App\Http\Requests\StoreStudent as StoreStudentRequest;

use Hash;

class StudentsController extends Controller
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
        $students = Student::orderBy('full_name', 'ASC')->get();
        return view('students.index', [
            'students' => $students
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
     * @param  App\Http\Requests\StoreStudent  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        $input = $request->validated();

        $student = new Student;

        $student->username = $input['username'];
        $student->password = Hash::make($input['password']);
        $student->full_name = ucwords($input['full_name']);
        $student->email = $input['email'];
        $student->personal_contact_number = $input['personal_contact_number'];
        $student->skype = $input['skype'];
        $student->birthday = date('Y-m-d', strtotime($input['birthday']));

        $student->save();

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
        $student = Student::find($id);

        return response()->json($student);
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
     * @param  App\Http\Requests\StoreStudent  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreStudentRequest $request, $id)
    {
        $input = $request->validated();

        $student = Student::find($id);

        $student->username = $input['username'];

        if (isset($input['password']) && !empty($input['password'])) {
            $student->password = Hash::make($input['password']);
        }

        $student->full_name = $input['full_name'];
        $student->email = $input['email'];
        $student->personal_contact_number = $input['personal_contact_number'];
        $student->skype = $input['skype'];
        $student->birthday = date('Y-m-d', strtotime($input['birthday']));

        $student->save();

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teacher = Student::find($id);
        $teacher->delete();

        return redirect(route('students.index'))->with('statusDelete', 'Successfully Deleted Student');
    }
}
