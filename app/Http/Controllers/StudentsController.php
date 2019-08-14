<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\ClassroomSchedulePreference;
use App\Classroom;

use App\Http\Requests\StoreStudent as StoreStudentRequest;

use Hash;
use DB;

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

        DB::transaction(function () use($input, $student) {
            $student->username = $input['username'];
            $student->password = Hash::make($input['password']);
            $student->full_name = ucwords($input['full_name']);
            $student->email = $input['email'];
            $student->personal_contact_number = $input['personal_contact_number'];
            $student->skype = $input['skype'];
            $student->birthday = date('Y-m-d', strtotime($input['birthday']));

            $student->save();

            $classroomSchedulePreference = new ClassroomSchedulePreference;
            $student->classroomSchedulePreference()->create($this->buildPrefSchedDaysInput($input));
        });

        return response()->json([
            'success' => true,
            'id' => $student->id
        ]);
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
            $student = Student::with('classroomSchedulePreference')->findOrFail($id);
            return response()->json($student);
        }

        $student = Student::with('classrooms', 'classroomSchedulePreference', 'transactions')->findOrFail($id);

        return view('students.show', [
            'student' => $student,
            'classroomMaxCreate' => Classroom::CREATE_MAX_PER_MONTH
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

            $classroomSchedulePreference = ClassroomSchedulePreference::where('student_id', $student->id)->first();
            $classroomSchedulePreference->fill($this->buildPrefSchedDaysInput($input));
            $classroomSchedulePreference->save();
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

    public function addBalance(Request $request)
    {
        $input = $request->validate([
            'id' => 'required|integer',
            'balance_amount_whole' => 'required|numeric',
            'balance_amount_decimal' => 'required|numeric'
        ]);

        $student = Student::findOrFail($input['id']);
        
        $amount = floatval($input['balance_amount_whole'] .'.'. $input['balance_amount_decimal']);
        $transaction = new \App\StudentTransaction;
        $transaction->amount = $amount;
        $transaction->description = 'Added '. number_format($amount, 2) .' KRW by Admin';
        $transaction->student_id = $student->id;

        $transaction->save();

        return redirect(route('students.show', ['id' => $student->id]))->with('balanceSuccess', 'Successfully added '. number_format($transaction->amount, 2) .' KRW');
    }

    private function buildPrefSchedDaysInput($input)
    {
        $daysToTable = [
            'M' => 'monday',
            'T' => 'tuesday',
            'W' => 'wednesday',
            'Th' => 'thursday',
            'F' => 'friday',
            'S' => 'saturday'
        ];

        $columnValue = [];
        foreach ($daysToTable as $code => $day) {
            $columnValue[$day] = in_array($code, $input['schedule_days']) ? 1 : 0;
        }

        $prefTimeStart = array_map('intval', explode(':', $input['schedule_start_time']));
        
        $columnValue['start_hour'] = $prefTimeStart[0];
        $columnValue['start_minute'] = $prefTimeStart[1];

        $columnValue['start_date'] = date('Y-m-d', strtotime($input['schedule_start_date']));

        return $columnValue;
    }
}
