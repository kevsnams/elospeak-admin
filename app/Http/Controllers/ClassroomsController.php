<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\ClassroomSchedulePreference;
use App\Student;
use App\Teacher;
use App\WebsiteSetting;

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
        if ($request->ajax()) {
            $classroom = Classroom::with('teacher')->findOrFail($id);

            return response()->json($classroom->toArray());
        }
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
        $request->validate([
            'teacher_id' => 'sometimes|exists:teachers,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'timeslot' => [
                'required',
                'regex:/[0-9][0-9]\:[0-9][0-9]|[0-9][0-9]\:[0-9][0-9]/'
            ],
            'date' => 'required|date_format:Y-n-j',
            'status' => 'required|in:'. implode(',', array_keys(Classroom::statusArray()))
        ]);

        $timeslots = explode('|', $request->input('timeslot'));
        
        $start = new Carbon($request->input('date') .' '. $timeslots[0]);
        $end = new Carbon($request->input('date') .' '. $timeslots[1]);

        $classroom = Classroom::findOrFail($request->input('classroom_id'));

        if ($request->input('teacher_id')) {
            $classroom->teacher_id = (int) $request->input('teacher_id');
        }

        $classroom->start = $start->format('Y-m-d H:i:s');
        $classroom->end = $end->format('Y-m-d H:i:s');
        $classroom->status = (int) $request->input('status');

        $classroom->save();

        return response()->json(['success' => true]);
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

    public function timeslots(Request $request)
    {
        $studentId = $request->input('student_id');
        $date = $request->input('date');

        $webSettings = parseWebSettings(WebsiteSetting::classrooms()->get());
        $timeslots = createClassroomTimeSlots(
            $webSettings['CLASSROOM']['start_hour'],
            $webSettings['CLASSROOM']['end_hour'],
            $webSettings['CLASSROOM']['duration']
        );

        $filledClassrooms = Classroom::where('student_id', $studentId)->whereRaw('DATE(start) = ?', [$date])->get();

        $foundKeys = [];
        foreach ($filledClassrooms as $classroom) {
            $foundKeys[] = array_search(date('H:i', strtotime($classroom->start)), array_column($timeslots, 0));
        }

        $availableSlots = array_values(
            array_filter($timeslots, function ($key) use ($foundKeys) {
                return !in_array($key, array_keys($foundKeys));
            }, ARRAY_FILTER_USE_KEY)
        );

        return response()->json($availableSlots);
    }

    public function teachers(Request $request)
    {
        $classroomId = $request->input('classroom_id');
        $search = trim($request->input('query'));

        $classroom = Classroom::with('teacher')->findOrFail($classroomId);
        $teachers = Teacher::where(function ($query) use ($search, $classroom) {
            $query->where('id', '<>', $classroom->teacher_id);
            if (strlen($search)) {
                $query->where(function($searchQuery) use ($search) {
                    $searchQuery->where('id', $search)
                        ->orWhere('full_name', 'LIKE', $search .'%')
                        ->orWhere('username', 'LIKE', $search .'%');
                });
            }
        })->whereDoesntHave('classrooms', function (Builder $query) use ($classroom) {
            $query->where('teacher_id', '<>', $classroom->teacher_id)
                ->where('start', '<>', $classroom->start)
                ->where('end', '<>', $classroom->end);
        })->orWhereHas('classrooms', function (Builder $query) use ($classroom) {
            $query->where('teacher_id', '<>', $classroom->teacher_id)
                ->where('start', '<>', $classroom->start)
                ->where('end', '<>', $classroom->end);
        })->get();

        return response()->json([
            'availableTeachers' => $teachers->toArray()
        ]);
    }
}
