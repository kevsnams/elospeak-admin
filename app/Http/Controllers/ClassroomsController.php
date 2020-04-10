<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\ClassroomSchedulePreference;
use App\Student;
use App\Teacher;
use App\WebsiteSetting;

use App\Elospeak\Timeslots;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

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
    public function index(Request $request)
    {
        $teacher = $request->teacher;
        $student = $request->student;
        $start = $request->start;
        $end = $request->end;
        $with = $request->input('with', []);

        $classrooms = Classroom::with($with)->where(function ($query) use ($teacher, $student, $start, $end) {
            if ($teacher) {
                $query->where('teacher_id', $teacher);
            }

            if ($student) {
                $query->where('student_id', $student);
            }

            if ($start) {
                $query->whereRaw('DATE(start) >= ?', [$start]);
            }

            if ($end) {
                $query->whereRaw('DATE(end) <= ?', [$end]);
            }
        })->get();

        return response()->json($classrooms->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $studentId = $request->input('student_id');
        $student = null;

        if ($studentId) {
            $student = Student::findOrFail($studentId);
        }

        return view('classrooms.create', [
            'student' => $student
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check enrollment controller
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $classroom = Classroom::with('teacher')->findOrFail($id);

        return response()->json($classroom->toArray());
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
        // TODO Make a custom Request object
        $isBatch = $id === 'batch';

        $rules = [
            'data.teacher' => [
                'sometimes',
                'required',
                'exists:teachers,id'
            ],

            'data.status' => [
                'sometimes',
                'required',
                Rule::in(Classroom::status()->pluck(0)->toArray())
            ],

            'data.start' => [
                'sometimes',
                'required',
                'date_format:Y-m-d H:i'
            ],

            'data.end' => [
                'sometimes',
                'required',
                'date_format:Y-m-d H:i'
            ]
        ];

        if ($isBatch) {
            $rules['data.ids'] = [
                'sometimes',
                'array',
                Rule::requiredIf($isBatch),
                Rule::exists('classrooms', 'id')->whereIn('id', $request->input('data.ids'))
            ];
        }

        $this->validate($request, $rules);

        if ($isBatch) {
            $model = Classroom::whereIn('id', $request->input('data.ids'));
        } else {
            $model = Classroom::where('id', $id);
        }

        $data = [];
        foreach ($request->input('data') as $col => $value) {
            if ($col == 'ids') {
                continue;
            }

            $newCol = $col;

            if ($col == 'teacher' || $col == 'student') {
                $newCol = $col .'_id';
            }

            if (filled($value)) {
                $data[$newCol] = $value;
            }
        }
        
        $model->update($data);

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
        //
    }

    public function getStatusAction()
    {
        return response()->json(Classroom::status()->toArray());
    }
}
