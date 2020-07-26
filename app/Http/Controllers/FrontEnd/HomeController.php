<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Student;
use App\Model\Course;
use App\Model\Status;
use App\Model\StudentFee;
use App\Model\Fee;
use App\Model\ReceiptDetail;
use App\Model\Transaction;
use App\User;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('frontEnd.home');
    }

    public function about()
    {
        return view('frontEnd.about');
    }

    public function contact()
    {
        return view('frontEnd.contact');
    }

    public function searchStudent(Request $request)
    {
    	$student = Student::where('student_id',$request->student_id)->first();
    	$status = Status::where('student_id',$request->student_id)->get();
        $sfees = StudentFee::join('levels','levels.level_id','=','student_fees.level_id')
                            ->join('programes','programes.programe_id','=','levels.programe_id')
                            ->join('fees','fees.fee_id','=','student_fees.fee_id')
                            ->where('student_fees.student_id',$request->student_id)
                            ->select(
                                'levels.level',
                                'levels.description as levelDescription',
                                'programes.programe',
                                'fees.amount as courseFee',
                                'student_fees.amount as studentFee',
                                'student_fees.total_paid',
                                'student_fees.discount',
                                'student_fees.s_fee_id'
                            )
                            ->get();
    	$ltransaction = Transaction::where('student_id',$request->student_id)->first();
    	// dd($student);
    	return view('frontEnd.student.student',compact('student','status','sfees','ltransaction'));
    }

    public function printFullInvoice($s_fee_id=null){

      $invoice = ReceiptDetail::join('receipts','receipts.receipt_id','=','receipt_details.receipt_id')
                    ->join('students','students.student_id','=','receipt_details.student_id')
                    ->join('transactions','transactions.transact_id','=','receipt_details.transact_id')
                    ->join('fees','fees.fee_id','=','transactions.fee_id')
                    ->join('levels','levels.level_id','=','fees.level_id')
                    ->join('programes','programes.programe_id','=','levels.programe_id')
                    ->join('users','users.id','=','transactions.user_id')
                    ->select('students.student_id',
                            'students.name as student_name',
                            'students.nick_name',
                            'students.sex',
                            'fees.amount as school_fee',
                            'fees.fee_id',
                            'transactions.transact_date',
                            'transactions.paid',
                            'users.name as user_name',
                            'transactions.s_fee_id',
                            'receipts.receipt_id',
                            'levels.level_id')
                    ->where('transactions.s_fee_id',$s_fee_id)
                    ->first();
        $courseDetails = Course::join('levels','levels.level_id','=','courses.level_id')
                            ->join('shifts','shifts.shift_id','=','courses.shift_id')
                            ->join('times','times.time_id','=','courses.time_id')
                            ->join('groups','groups.group_id','=','courses.group_id')
                            ->join('batches','batches.batch_id','=','courses.batch_id')
                            ->join('academics','academics.academic_id','=','courses.academic_id')
                            ->join('programes','programes.programe_id','=','levels.programe_id')
                            ->join('statuses','statuses.course_id','=','courses.course_id')
                            ->where('levels.level_id',$invoice->level_id)
                            ->where('statuses.student_id',$invoice->student_id)
                            ->select(DB::raw('CONCAT(programes.programe,
                                              " / Level-",levels.level,
                                              " / Shift-",shifts.shift,
                                              " / Time-",times.time,
                                              " / Group-",groups.group,
                                              " / Batch-",batches.batch,
                                              " / Academic-",academics.academic,
                                              " / Start Date-",courses.start_date,
                                              " / ",courses.end_date
                                              ) As detail'))
                            ->first();
        $studentFee = StudentFee::where('s_fee_id',$invoice->s_fee_id)->first();
        $totalPaid = Transaction::where('s_fee_id',$invoice->s_fee_id)->sum('paid');                    
        $totalPaids = Transaction::where('s_fee_id',$invoice->s_fee_id)->get();                    

      return view('backEnd.invoice.fullInvoice',compact('invoice','courseDetails','studentFee','totalPaid','totalPaids'));
    }






















}
