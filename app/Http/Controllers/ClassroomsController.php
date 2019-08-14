<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classroom;
use App\ClassroomSchedulePreference;
use App\Student;

class ClassroomsController extends Controller
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
        return view('classrooms.index', [
            'classrooms' => Classroom::all()
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
    public function store(Request $request)
    {
        // Perform validation first
        $request->validate([
            'classroom.quantity' => 'required|integer|min:1|max:'. Classroom::CREATE_MAX_PER_MONTH,
            'classroom.use_schedule_preference' => 'sometimes',
            'classroom.start_time' => [
                'required_if:use_schedule_preference,on',
                'regex:/[0-9][0-9]\:[0-9][0-9]/'
            ],
            'classroom.start_date' => 'required_if:use_schedule_preference,on|date_format:d F Y',
            'classroom.student_id' => 'required|exists:students,id'
        ]);

        $student = Student::findOrFail($request->input('classroom.student_id'));
        $quantity = $request->input('classroom.quantity', 1);
        $useSchedulePreference = $request->input('classroom.use_schedule_preferences') ? true : false;
        
        // Determine what to use as datetime string
        $startDateTimeString = '';

        // If the user wants to use the student's schedule preference
        if ($useSchedulePreference) {
            // Then fetch from database, and use it.
            $schedulePreference = ClassroomSchedulePreference::where('student_id', $student->id)->first();
            $startDateTimeString = $schedulePreference->start_date .' '. $schedulePreference->start_hour .':'. $schedulePreference->start_minute;
        } else {
            // Otherwise, just use the input
            $startDateTimeString = $request->input('classroom.start_date') .' '. $request->input('classroom.start_time');
        }

        $startDateTime = new Carbon($startDateTimeString);
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
