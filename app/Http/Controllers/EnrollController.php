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

class EnrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
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

        DB::transaction(function () use ($input, $webSettings, &$studentId) {
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

            $totalAmount = 0;
            $pdfData = [];

            foreach ($input['classroom_schedule_preference'] as $day => $timeSlots) {
                $timeSlots = revertClassroomTimeSlotsValues($timeSlots['slots']);

                $classroomSchedulePreference->{$day} = json_encode($timeSlots);

                foreach ($timeSlots as $timeSlot) {
                    // It's the weekend!
                    if ($day == 'saturday' || $day == 'sunday') {
                        $totalAmount += $webSettings['CLASSROOM']['price_per_class_weekend'];
                    } else {
                        $totalAmount += $webSettings['CLASSROOM']['price_per_class'];
                    }
                }

                $pdfData[$day] = $timeSlots;
            }

            $studentTransaction->amount = $totalAmount;
            $studentTransaction->description = 'New enrollment. View invoice for more details';

            $studentTransaction->save();
            $classroomSchedulePreference->save();

            // Create invoice
            $invoice = new Invoice();

            $invoice->pdf_path = '';
            $invoice->student_id = $student->id;
            $invoice->transaction_id = $studentTransaction->id;
            $invoice->save();

            $pdf = PDF::loadView('pdf.invoice', [
                'student' => $student,
                'invoice' => $invoice,
                'classes' => $pdfData,
                'totalAmount' => $totalAmount,
                'transaction_id' => $studentTransaction->id,
                'pricePerClassWeekday' => $webSettings['CLASSROOM']['price_per_class'],
                'pricePerClassWeekend' => $webSettings['CLASSROOM']['price_per_class_weekend']
            ]);

            $invoiceFilename = 'invoice_'. $studentTransaction->id .'-'. $invoice->id .'.pdf';
            $invoice->pdf_path = $invoiceFilename;
            $invoice->save();

            $pdf->save(base_path('/invoices/'. $invoiceFilename));
            
            $studentId = $student->id;
            
            // Temporary deletion
            $invoice->delete();
            $studentTransaction->delete();
            $student->delete();
        });

        return response()->json([
            'success' => true,
            'student_id' => $studentId
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
