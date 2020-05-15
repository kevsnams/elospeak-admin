<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $request->validate([
            'data.start_date' => [
                'required',
                'date',
                'date_format:Y-m-d'
            ],

            'data.start_time' => [
                'required',
                'string',
                'regex:/^\d{1,2}\:\d{1,2}$/'
            ],

            'data.duration' => [
                'required',
                'numeric'
            ],

            'data.teacher' => [
                'required',
                'exists:App\Teacher,id'
            ],

            'data.student' => [
                'required',
                'exists:App\Student,id'
            ],

            'data.price' => [
                'required',
                'filled',
                'numeric'
            ]
        ]);

        $data = $request->input('data');

        $date = $data['start_date'] .' '. $data['start_time'];
        $date = Carbon::createFromFormat('Y-m-d H:i', $date);

        $start = $date->format('Y-m-d H:i');
        $end = $date->addMinutes($data['duration'])->format('Y-m-d H:i');

        $classroom = new Classroom();
        $classroom->start = $start;
        $classroom->end = $end;
        $classroom->teacher_id = $data['teacher'];
        $classroom->student_id = $data['student'];
        $classroom->price = $data['price'];
        $classroom->status = Classroom::STATUS_ACTIVE;
        $classroom->save();

        return redirect(route(
            'students.show',
            [ 'student' => $data['student'] ]
        ))->with('message', 'Successfully created a classroom');
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
    public function edit(Request $request, $id)
    {
        $classroom = Classroom::with('student', 'teacher')->findOrFail($id);
        $teachers = Teacher::orderBy('full_name', 'asc')->get();
        $status = [
            [ Classroom::STATUS_ACTIVE, 'Active' ],
            [ Classroom::STATUS_DONE, 'Done' ],
            [ Classroom::STATUS_CANCELLED, 'Cancelled' ]
        ];

        $fromPage = $request->input('from');

        return view(
            'classroom.edit',
            compact('classroom', 'fromPage', 'status', 'teachers')
        );
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
        $classroom = Classroom::findOrFail($id);
        $request->validate([
            'data.teacher' => [
                'sometimes',
                'nullable',
                'exists:App\Teacher,id'
            ],

            'data.start_date' => [
                'required',
                'date',
                'date_format:Y-m-d'
            ],

            'data.start_time' => [
                'required',
                'string',
                'regex:/^\d{1,2}\:\d{1,2}$/'
            ],

            'data.duration' => [
                'required',
                'numeric'
            ],

            'data.status' => [
                'required',
                'numeric',
                Rule::in([ Classroom::STATUS_ACTIVE, Classroom::STATUS_DONE, Classroom::STATUS_DONE ])
            ],

            'data.price' => [
                'required',
                'filled',
                'numeric'
            ]
        ]);

        $data = $request->input('data');

        $date = Carbon::createFromFormat('Y-m-d H:i', implode(' ', [ $data['start_date'], $data['start_time'] ]));
        $start = $date->format('Y-m-d H:i');
        $end = $date->addMinutes($data['duration'])->format('Y-m-d H:i');

        $classroom->start = $start;
        $classroom->end = $end;
        $classroom->status = $data['status'];
        $classroom->price = $data['price'];
        $classroom->teacher_id = $data['teacher'];
        $classroom->save();

        return redirect()->back()->with('message', 'Successfully update classroom');
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
