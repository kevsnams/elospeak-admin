<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\ClassroomSchedulePreference;
use App\Classroom;
use App\WebsiteSetting;

use App\Http\Requests\StoreStudent as StoreStudentRequest;

use Hash;
use DB;

use Carbon\Carbon;

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

        return response()->json($students->toArray());
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

    public function store(StoreStudentRequest $request)
    {
        $input = $request->validated();
        $student = new Student();

        foreach ($request->validated()['data'] as $col => $value) {
            if ($col == 'password_repeat') {
                continue;
            }

            $finalValue = $value;
            
            if ($col == 'password') {
                $finalValue = Hash::make($value);
            }

            if ($col == 'birthday') {
                $finalValue = date('Y-m-d', strtotime($value));
            }

            $student->{$col} = $finalValue;
        }

        $student->save();

        return response()->json([$student->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $student = Student::with($request->input('with', []))->findOrFail($id);

        return response()->json($student->toArray());
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
        $request->validated();

        $student = Student::findOrFail($id);

        foreach ($request->validated()['data'] as $col => $value) {
            if ($col == 'new_password_repeat') {
                continue;
            }

            $finalValue = $value;
            $finalCol = $col;

            if ($col == 'new_password') {
                $finalValue = Hash::make($value);
                $finalCol = 'password';
            }

            if ($col == 'birthday') {
                $finalValue = date('Y-m-d', strtotime($value));
            }

            $student->{$finalCol} = $finalValue;
        }

        $student->save();

        return response()->json([1]);
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
        $student->delete();

        return response()->json([true]);
    }
}
