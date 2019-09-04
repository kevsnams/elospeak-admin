<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Student;
use App\ClassroomSchedulePreference;
use App\StudentTransaction;
use App\WebsiteSetting;
use App\Invoice;
use App\Classroom;

use App\Elospeak\Timeslots;
use App\Http\Requests\StoreEnrollment as StoreEnrollmentRequest;

use Hash;
use DB;
use PDF;
use Carbon\Carbon;

class EnrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $timeslots = new Timeslots();

        return view('enroll.index', [
            'timeSlots' => $timeslots->getSlots()
        ]);
    }

    public function store(StoreEnrollmentRequest $request) {
        $input = $request->validated();

        $webSettings = parseWebSettings(WebsiteSetting::classrooms(['price_per_class_weekend', 'price_per_class'])->get());
        $studentId = null;
        $invoiceId = null;

        DB::transaction(function () use ($input, $webSettings, &$studentId, &$invoiceId) {
            // Save Student
            $student = new Student();
            $student->username = $input['student']['username'];
            $student->password = Hash::make($input['student']['password']);
            $student->full_name = $input['student']['full_name'];
            $student->email = $input['student']['email'];
            $student->personal_contact_number = $input['student']['personal_contact_number'];
            $student->skype = $input['student']['skype'];
            $student->birthday = date('Y-m-d', strtotime($input['student']['birthday']));

            $student->save();

            // Save preferred schedule
            $classroomSchedulePreference = new ClassroomSchedulePreference();
            $classroomSchedulePreference->student_id = $student->id;

            // Create student transaction entry
            $studentTransaction = new StudentTransaction();
            $studentTransaction->student_id = $student->id;
            $studentTransaction->description = 'New enrollment. View invoice for more details';
            $studentTransaction->invoice_id = 0;
            $studentTransaction->amount = 0;
            $studentTransaction->save();

            // Create invoice
            $invoice = new Invoice();
            $invoice->pdf_path = '';
            $invoice->student_id = $student->id;
            $invoice->status = Invoice::STATUS_UNPAID;
            $invoice->student_transaction_id = $studentTransaction->id;
            $invoice->save();

            $invoiceId = $invoice->id;

            $totalClasses = 0;
            $totalAmount = 0;
            $pdfData = [];

            $startDate = new Carbon($input['start_date']);

            // This will contain all the classrooms that will be inserted to classrooms table
            $classrooms = [];

            /**
             * Total amount calculation
             * Start with week 1
             */
            $week = 1;
            $date = new Carbon($input['start_date']);
            // @TODO Korean Format
            // $date->locale('ko_KR');

            $timeslots = new Timeslots();

            for ($iday = 1; $iday <= Classroom::PAYMENT_NUM_DAYS_CYCLE; $iday++) {
                $day = $input['classroom_schedule_preference'][strtolower($date->englishDayOfWeek)] ?? null;

                // If $day is not on on classroom_schedule_preference
                if (is_null($day)) {
                    // Go to the next day
                    $date->setTimeFrom($date->addDay()->format('Y-m-d'));
                    continue;
                }

                $timeslots->setSlots($day['slots'], '|');
                $classes = [];

                foreach ($timeslots->getSlots() as $timeslot) {
                    $price = $date->isWeekend() ? $webSettings['CLASSROOM']['price_per_class_weekend'] : $webSettings['CLASSROOM']['price_per_class'];

                    $classes[] = [
                        'start' => $timeslot[0],
                        'end' => $timeslot[1],
                        'price' => $price
                    ];

                    $totalAmount += $price;
                    $totalClasses++;

                    $classrooms[] = [
                        'student_id' => $student->id,
                        'status' => Classroom::STATUS_UNPAID,
                        'price' => $price,
                        'invoice_id' => $invoice->id,
                        'start' => date('Y-m-d H:i', strtotime($date->format('Y-m-d') .' '. $timeslot[0])),
                        'end' => date('Y-m-d H:i', strtotime($date->format('Y-m-d') .' '. $timeslot[1])),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                $classroomSchedulePreference->{strtolower($date->englishDayOfWeek)} = json_encode($timeslots->getSlots());

                $pdfData[] = [
                    'date' => $date->format('l, F j, Y'),
                    'slots' => $classes
                ];

                // Go to the next day
                $date->setTimeFrom($date->addDay()->format('Y-m-d'));
            }

            // Save classrooms
            Classroom::insert($classrooms);

            // Save the classroom schedule preference
            $classroomSchedulePreference->save();

            // Update the student transaction to include total amount and invoice id
            $studentTransaction->amount = $totalAmount;
            $studentTransaction->invoice_id = $invoice->id;
            $studentTransaction->save();

            $pdf = PDF::loadView('pdf.invoice', [
                'student' => $student,
                'invoice' => $invoice,
                'classes' => $pdfData,
                'startDate' => $startDate,
                'endDate' => $date,
                'totalClasses' => $totalClasses,
                'totalAmount' => $totalAmount,
                'transaction_id' => $studentTransaction->id
            ]);

            /**
             * @TODO KOREAN FONTS!
             */
            /*PDF::setOptions([
                'defaultFont' => 'NanumGothic',
                'isFontSubsettingEnabled' => true
            ]);
            /** END */
            $invoiceFilename = 'invoice_'. $studentTransaction->id .'-'. $invoice->id .'.pdf';
            $invoice->pdf_path = $invoiceFilename;
            $invoice->save();

            $pdf->save(base_path('/invoices/'. $invoiceFilename));
            
            $studentId = $student->id;
        });

        return response()->json([
            'success' => true,
            'student_id' => $studentId,
            'invoice_id' => $invoiceId
        ]);
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:students,email|email',
            'username' => 'required|unique:students,username|min:6|max:50'
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
