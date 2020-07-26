<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Model\Status;
use App\Model\Student;
use App\Model\StudentFee;
use App\Model\ReceiptDetail;
use App\Model\Transaction;
class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAllStudentReport(){
        $studentReports = $this->showStudentInfo();
        return view('backEnd.report.allStudentReport', compact('studentReports'));
    }

    public function getStudentReportByCourse(Request $request){
        $studentReports = $this->showStudentInfoByCourse($request->course_id);
        return view('backEnd.report.studentReport', compact('studentReports'));
    }

    public function globalSearchStudentReport(){

      if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') || \Gate::allows('isUser') ) {

            if ($search = \Request::get('q')) {
                $studentReports = Student::join('statuses','statuses.student_id','=','students.student_id')
                                   ->join('courses','courses.course_id','=','statuses.course_id')
                                   ->join('levels','levels.level_id','=','courses.level_id')
                                   ->join('programes','programes.programe_id','=','levels.programe_id')
                                   ->join('academics','academics.academic_id','=','courses.academic_id')
                                   ->join('shifts','shifts.shift_id','=','courses.shift_id')
                                   ->join('groups','groups.group_id','=','courses.group_id')
                                   ->join('times','times.time_id','=','courses.time_id')
                                   ->join('batches','batches.batch_id','=','courses.batch_id')
                                   ->select(DB::raw('students.student_id,
                                                    CONCAT(students.name," ",students.nick_name) as name,
                                                    (CASE WHEN students.sex=0 THEN "Male" ELSE "Female" END) as sex,
                                                    students.dob,
                                                    students.phone,
                                                    academics.academic,
                                                    batches.batch,
                                                    groups.group,
                                                    programes.programe'))
                          ->where(function($query) use ($search){
                                $query->where('students.name', 'LIKE', "%$search%")
                                      ->orWhere('students.nick_name', 'LIKE', "%$search%")
                                      ->orWhere('programes.programe', 'LIKE', "%$search%")
                                      ->orWhere('students.email', 'LIKE', "%$search%")
                                      ->orWhere('students.phone', 'LIKE', "%$search%")
                                      ->orWhere('batches.batch', 'LIKE', "%$search%")
                                      ->orWhere('groups.group', 'LIKE', "%$search%")
                                      ->orWhere('students.student_id','LIKE', "%$search%");
                          })->get();
            } else {

                $studentReports = $this->showStudentInfo();
            }

        
          return view('backEnd.report.allStudentReport', compact('studentReports'));
      }
    }
    public function showStudentInfo(){

        return Student::join('statuses','statuses.student_id','=','students.student_id')
                     ->join('courses','courses.course_id','=','statuses.course_id')
                     ->join('levels','levels.level_id','=','courses.level_id')
                     ->join('programes','programes.programe_id','=','levels.programe_id')
                     ->join('academics','academics.academic_id','=','courses.academic_id')
                     ->join('shifts','shifts.shift_id','=','courses.shift_id')
                     ->join('groups','groups.group_id','=','courses.group_id')
                     ->join('times','times.time_id','=','courses.time_id')
                     ->join('batches','batches.batch_id','=','courses.batch_id')
                     ->select(DB::raw('students.student_id,
                                      CONCAT(students.name," ",students.nick_name) as name,
                                      (CASE WHEN students.sex=0 THEN "Male" ELSE "Female" END) as sex,
                                      students.dob,
                                      students.phone,
                                      academics.academic,
                                      batches.batch,
                                      groups.group,
                                      programes.programe'))
                     ->get();

    }
    public function showStudentInfoByCourse($course_id){

        return Status::join('students','students.student_id','=','statuses.student_id')
                     ->join('courses','courses.course_id','=','statuses.course_id')
                     ->join('levels','levels.level_id','=','courses.level_id')
                     ->join('programes','programes.programe_id','=','levels.programe_id')
                     ->join('academics','academics.academic_id','=','courses.academic_id')
                     ->join('shifts','shifts.shift_id','=','courses.shift_id')
                     ->join('groups','groups.group_id','=','courses.group_id')
                     ->join('times','times.time_id','=','courses.time_id')
                     ->join('batches','batches.batch_id','=','courses.batch_id')
                     ->select(DB::raw('students.student_id,
                                      CONCAT(students.name," ",students.nick_name) as name,
                                      (CASE WHEN students.sex=0 THEN "Male" ELSE "Female" END) as sex,
                                      students.dob,
                                      students.phone,
                                      academics.academic,
                                      batches.batch,
                                      groups.group,
                                      programes.programe,
                                      CONCAT(programes.programe," / Lavel: ", levels.level," / Shift: ",shifts.shift," / Batch: ", batches.batch," / Group: ",groups.group," / Time: ",times.time," / StartDate: ",courses.start_date," / EndDate:",courses.end_date) as details'))
                     ->where('courses.course_id',$course_id)
                     ->get();

    }

    public function getTransactionReportByDate(Request $request){
       $from = Carbon::parse($request->from)->format('Y-m-d');
       $to = Carbon::parse($request->to)->format('Y-m-d');
        $transactions = $this->transactionInfo()
                      ->select("users.name as cashier",
                               "students.student_id",
                               "students.name as studentName",
                               "students.nick_name",
                               "transactions.transact_date",
                               "fees.amount AS course_fee",
                               "student_fees.amount AS student_fee",
                               "student_fees.discount",
                               "transactions.paid")
                      ->whereDate('transactions.transact_date','>=',$from)
                      ->whereDate('transactions.transact_date','<=',$to)
                      ->orderBy('students.student_id')
                      ->get();
      return view('backEnd.report.transactionReport',compact('transactions'));
    }
    public function transactionInfo(){

      return ReceiptDetail::join('students','students.student_id','=','receipt_details.student_id')
                          ->join('transactions','transactions.transact_id','=','receipt_details.transact_id')
                          ->join('student_fees','student_fees.s_fee_id','=','transactions.s_fee_id')
                          ->join('fees','fees.fee_id','=','transactions.fee_id')
                          ->join('users','users.id','=','transactions.user_id')
                          ->orderBy('transactions.transact_date');
                          
    }
    public function getAllTransactionReport(){

      $transactions = $this->transactionInfo()
                      ->select("users.name as cashier",
                               "students.student_id",
                               "students.name as studentName",
                               "students.nick_name",
                               "transactions.transact_date",
                               "fees.amount AS course_fee",
                               "student_fees.amount AS student_fee",
                               "student_fees.discount",
                               "transactions.paid")
                      ->get();
      return view('backEnd.report.transactionReport',compact('transactions'));
                          
    }

    public function getAllPaymentReport(){
      $studentFee = StudentFee::join('fees','fees.fee_id','=','student_fees.fee_id')
                             ->join('students','students.student_id','=','student_fees.student_id')
                             ->join('levels','levels.level_id','=','student_fees.level_id')
                             ->join('programes','programes.programe_id','=','levels.programe_id')
                             ->join('feetypes','feetypes.fee_type_id','=','fees.fee_type_id')
                             ->select(
                                  'student_fees.student_id',
                                  'students.name as studentName',
                                  'programes.programe',
                                  'levels.level',
                                  'feetypes.fee_type',
                                  'student_fees.amount as studentAmount',
                                  'student_fees.s_fee_id',
                                  'student_fees.discount',
                                  'fees.amount as courseFee'
                                )
                            ->orderBy('student_fees.student_id')
                            ->get();
              
             $transact = Transaction::get();
             return view('backEnd.report.paymentReport',compact('studentFee','transact'));
    }
    public function globalSearchStudentPaymentReport(){
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') || \Gate::allows('isUser') ) {

            if ($search = \Request::get('q')) {
                $studentFee = StudentFee::join('fees','fees.fee_id','=','student_fees.fee_id')
                             ->join('students','students.student_id','=','student_fees.student_id')
                             ->join('levels','levels.level_id','=','student_fees.level_id')
                             ->join('programes','programes.programe_id','=','levels.programe_id')
                             ->join('feetypes','feetypes.fee_type_id','=','fees.fee_type_id')
                             ->select(
                                  'student_fees.student_id',
                                  'students.name as studentName',
                                  'levels.level',
                                  'programes.programe',
                                  'feetypes.fee_type',
                                  'student_fees.amount as studentAmount',
                                  'student_fees.s_fee_id',
                                  'student_fees.discount',
                                  'fees.amount as courseFee'
                                )
                          ->where(function($query) use ($search){
                                $query->where('students.name', 'LIKE', "%$search%")
                                      ->orWhere('students.nick_name', 'LIKE', "%$search%")
                                      ->orWhere('programes.programe', 'LIKE', "%$search%")
                                      ->orWhere('feetypes.fee_type', 'LIKE', "%$search%")
                                      ->orWhere('fees.fee_heading', 'LIKE', "%$search%")
                                      ->orWhere('students.email', 'LIKE', "%$search%")
                                      ->orWhere('students.student_id','LIKE', "%$search%");
                          })->get();
            } else{
                $studentFee = StudentFee::join('fees','fees.fee_id','=','student_fees.fee_id')
                             ->join('students','students.student_id','=','student_fees.student_id')
                             ->join('levels','levels.level_id','=','student_fees.level_id')
                             ->join('programes','programes.programe_id','=','levels.programe_id')
                             ->join('feetypes','feetypes.fee_type_id','=','fees.fee_type_id')
                             ->select(
                                  'student_fees.student_id',
                                  'students.name as studentName',
                                  'programes.programe',
                                  'levels.level',
                                  'feetypes.fee_type',
                                  'student_fees.amount as studentAmount',
                                  'student_fees.s_fee_id',
                                  'student_fees.discount',
                                  'fees.amount as courseFee'
                                )
                            ->orderBy('student_fees.student_id')
                            ->get();
            }
            $transact = Transaction::get();
             return view('backEnd.report.paymentReport',compact('studentFee','transact'));

        }
    }



}
