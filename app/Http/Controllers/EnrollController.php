<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Student;
use App\ClassroomSchedulePreference;
use App\StudentTransaction;
use App\WebsiteSetting;
use App\Invoice;
use App\Classroom;

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
        $t = new \App\Elospeak\Timeslots(8, 22, 25);
        $d = \App\Elospeak\Timeslots::getAvailable('2019-09-01');
        dd($d->getSlots(), $d->flatten());
        #dd($t->getSlots(), $t->flatten());

        $webSettings = parseWebSettings(WebsiteSetting::classrooms()->get());
        $classroomTimeSlots = createClassroomTimeSlots(
            // Opens 7AM
            $webSettings['CLASSROOM']['start_hour'],

            // Closes 11 PM
            $webSettings['CLASSROOM']['end_hour'],

            // Class duration
            $webSettings['CLASSROOM']['duration']
        );

        return view('enroll.index', [
            'timeSlots' => $classroomTimeSlots
        ]);
    }

    public function store(StoreEnrollmentRequest $request) {
        $input = $request->validated();

        // Get the website settings
        // @TODO Probably select only what we want?
        $webSettings = parseWebSettings(WebsiteSetting::classrooms()->get());
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

            while ($week <= Classroom::PAYMENT_NUM_WEEKS_CYCLE) {
                $day = $input['classroom_schedule_preference'][strtolower($date->englishDayOfWeek)] ?? null;

                // If $startDate is not found on classroom_schedule_preference
                if (is_null($day)) {
                    // If sunday, go to the next week
                    if ($date->dayOfWeek === Carbon::SUNDAY) {
                        $week += 1;
                    }

                    // Go to the next day
                    $date->setTimeFrom($date->addDay()->format('Y-m-d'));
                    continue;
                }

                $timeSlots = revertClassroomTimeSlotsValues($day['slots']);
                $slots = [];

                foreach ($timeSlots as $timeSlot) {
                    $price = $date->isWeekend() ? $webSettings['CLASSROOM']['price_per_class_weekend'] : $webSettings['CLASSROOM']['price_per_class'];

                    $slots[] = [
                        'start' => $timeSlot[0],
                        'end' => $timeSlot[1],
                        'price' => $price
                    ];

                    $totalAmount += $price;
                    $totalClasses += 1;

                    $classrooms[] = [
                        'student_id' => $student->id,
                        'status' => Classroom::STATUS_UNPAID,
                        'price' => $price,
                        'invoice_id' => $invoice->id,
                        'start' => date('Y-m-d H:i', strtotime($date->format('Y-m-d') .' '. $timeSlot[0])),
                        'end' => date('Y-m-d H:i', strtotime($date->format('Y-m-d') .' '. $timeSlot[1])),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }

                $classroomSchedulePreference->{strtolower($date->englishDayOfWeek)} = json_encode($timeSlots);

                $pdfData[] = [
                    // @TODO Korean fonts
                    // 'date' => $date->isoFormat('Do MMMM'),
                    'date' => $date->format('l, F j, Y'),
                    'slots' => $slots
                ];

                // Go to the next day
                $date->setTimeFrom($date->addDay()->format('Y-m-d'));

                // If after all that shinanigans, if the day is sunday, then proceed to the next week
                if ($date->dayOfWeek === Carbon::SUNDAY) {
                    $week += 1;
                }
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
