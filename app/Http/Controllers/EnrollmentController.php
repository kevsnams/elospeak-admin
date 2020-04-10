<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use PDF;
use Storage;

use App\Country;
use App\Student;
use App\Classroom;
use App\Enrollment;

class EnrollmentController extends Controller
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
        $classrooms = Enrollment::where(
            function ($query) use ($request) {
                if ($request->input('student')) {
                    $query->where('student_id', $request->student);
                }
            }
        )
        ->with($request->input('with', []))
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json($classrooms->toArray());
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
        error_reporting(E_ALL ^ E_DEPRECATED);

        $dataset = $request->validate([
            'data.start' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:data.end'
            ],

            'data.end' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:data.start'
            ],

            'data.student_id' => [
                'required',
                'exists:students,id'
            ],

            'data.dates.*.start' => [
                'required',
                'date_format:Y-m-d H\:i'
            ],
            'data.dates.*.end' => [
                'required',
                'date_format:Y-m-d H\:i'
            ]
        ]);
        
        $student = Student::findOrFail($dataset['data']['student_id']);

        if ($student->has_active_enrollment) {
            abort(422, 'Unauthorized: Student have an active enrollment');
        }

        $country = Country::where('code_iso3166_a2', $student->country_code)->firstOrFail();

        if (!$country->is_defined) {
            abort(422, 'Error: Student\'s country is not defined');
        }

        $enrollment = new Enrollment();
        $enrollment->active = Enrollment::ACTIVE;
        $enrollment->paid = Enrollment::UNPAID;

        $items = collect();

        try {
            DB::transaction(function () use ($enrollment, $student, $country, $dataset, $items) {
                $enrollment->student_id = $student->id;
                $enrollment->save();

                foreach ($dataset['data']['dates'] as $date) {
                    $start = Carbon::createFromFormat('Y-m-d H:i', $date['start']);
                    $end = Carbon::createFromFormat('Y-m-d H:i', $date['end']);

                    $classroom = new Classroom();
                    $classroom->price = $start->isWeekend() ? $country->price_weekend : $country->price;
                    $classroom->start = $start;
                    $classroom->end = $end;
                    $classroom->student_id = $student->id;
                    $classroom->status = Classroom::STATUS_ACTIVE;
                    $classroom->enrollment_id = $enrollment->id;
                    $classroom->save();

                    $items->push([
                        'date' => $start->format('Y-m-d'),
                        'start' => $start,
                        'end' => $end,
                        'price' => $classroom->price,
                        'is_weekend' => $start->isWeekend(),
                        'type' => $start->isWeekend() ? 'Weekend Classes' : 'Weekday Classes'
                    ]);
                }
            }, 3);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }

        
        $pdf = PDF::loadView('pdf.invoice', [
            'student' => $student,
            'country' => $country,
            'enrollment' => $enrollment,
            'items' => $items
        ]);
        
        $disk = Storage::disk('shared_local');

        if (!$disk->exists('/invoices')) {
            $disk->makeDirectory('/invoices');
        }

        $pdfFilename = $enrollment->id .'_'. md5($enrollment->id . $enrollment->created_at->format('Y-m-d-H-i')) .'.pdf';
        $pdfFolder = $disk->path('/invoices');
        $pdfPath = $pdfFolder .'/'. $pdfFilename;

        $pdf->save($pdfPath);

        $enrollment->pdf_path = '/invoices/'. $pdfFilename;
        $enrollment->save();

        return response()->json($enrollment->toArray());
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
        $request->validate([
            'data.active' => [
                'sometimes',
                'required',
                'boolean'
            ],

            'data.paid' => [
                'sometimes',
                'required',
                'boolean'
            ]
        ]);

        $enrollment = Enrollment::findOrFail($id);

        foreach ($request->input('data') as $column => $value) {
            $enrollment->{$column} = $value;
        }

        $enrollment->save();

        return response()->json([true]);
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

    public function pdf(Request $request, $id)
    {
        $enrollment = Enrollment::where('student_id', $request->user()->id)->findOrfail($id);

        return Storage::disk('shared_local')->download(
            $enrollment->pdf_path,
            'invoice_'. $enrollment->id .'.pdf'
        );
    }
}
