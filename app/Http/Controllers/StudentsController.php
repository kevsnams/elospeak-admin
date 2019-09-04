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
    public function index(Request $request)
    {
        $searchQuery = trim($request->input('query'));
        $students = Student::orderBy('full_name', 'ASC')->where(function ($query) use ($searchQuery) {
            if ($searchQuery) {
                $query->where('full_name', 'LIKE', "$searchQuery%")
                    ->orWhere('username', 'LIKE', "$searchQuery%");
            }
        })->get();

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

    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $student = Student::with('classrooms', 'classroomSchedulePreference', 'transactions', 'transactions.invoice')->findOrFail($id);

        return view('students.show', [
            'student' => $student
        ]);
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

        $student = Student::findOrFail($id);

        DB::transaction(function () use ($input, $student, $id) {
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
        });

        return response()->json([
            'success' => true,
            'id' => $student->id
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
        DB::transaction(function () use ($id) {
            $student = Student::find($id);
            $student->delete();

            ClassroomSchedulePreference::where('student_id', $student->id)->delete();
        });

        return redirect(route('students.index'))->with('statusDelete', 'Successfully Deleted Student');
    }

    public function classrooms(Request $request, $id, $view = 'weekly', $date = null)
    {
        $student = Student::findOrFail($id);

        $startOfMonth = null;
        $endOfMonth = null;
        
        $now = new Carbon();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $sidebar = [];

        $isFilter = false;
        $filterYear = null;
        $filterMonth = null;
        $dateDay = null;

        // Ensure $view will only be 'date' & 'monthly'
        switch ($view) {
            case 'date':
                $view = 'date';
                break;
            case 'monthly':
            default:
                $view = 'monthly';
                
                $filterMonth = $request->input('filter_month');
                $filterYear = $request->input('filter_year');

                $isFilter = !is_null($filterMonth) && !is_null($filterYear);
                $filterCarbon = $isFilter ? Carbon::createFromDate($filterYear, ($filterMonth + 1), 1) : $now;

                $startOfMonth = new Carbon($filterCarbon->startOfMonth());
                $endOfMonth = new Carbon($filterCarbon->endOfMonth());
                break;
        }

        $classrooms = [];

        if ($view == 'monthly') {
            $rows = Classroom::where('start', '>', $startOfMonth->format('Y-m-d H:i:s'))
                ->where('end', '<', $endOfMonth->format('Y-m-d H:i:s'))
                ->where('student_id', $student->id)->get();
            
            foreach ($rows as $row) {
                $classrooms[$row->start->format('Ymd')][] = $row;
            }
        }

        if ($view == 'date') {
            $dateDay = new Carbon($date);
            $classrooms = Classroom::where('student_id', $id)
                ->whereRaw('DATE(`start`) = ?', [$dateDay->format('Y-m-d')])->get();
        }

        $firstClassroom = Classroom::orderBy('start', 'ASC')->first();
        $lastClassroom = Classroom::orderBy('end', 'DESC')->first();

        $filterYears = [];
        for ($i = (int) $firstClassroom->start->format('Y'); $i <= (int) $lastClassroom->end->format('Y'); $i++) {
            $filterYears[] = $i;
        }

        $filterMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        return view('students.classrooms', [
            'now' => $now,
            'view' => $view,
            'days' => $days,
            'student' => $student,
            'classroomStatus' => Classroom::statusArray(),
            'startOfMonth' => $startOfMonth,
            'endOfMonth' => $endOfMonth,
            'classrooms' => $classrooms,
            'filterYears' => $filterYears,
            'filterYear' => $filterYear,
            'filterMonths' => $filterMonths,
            'filterMonth' => $filterMonth,
            'isFilter' => $isFilter,
            'dateDay' => $dateDay
        ]);
    }

    public function addClassroom(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        return view('students.add_classroom', [
            'student' => $student
        ]);
    }
}
