<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Model\Student;
use App\Model\Status;
use App\Model\Feetype;
use App\Model\Fee;
use App\Model\Level;
use App\Model\Programe;
use App\Model\StudentFee;
use App\Model\Transaction;
use App\Model\Receipt;
use App\Model\ReceiptDetail;
use App\Model\Course;
use DB;
class FeeController extends Controller
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

  
    public function getStudentDetail(Request $request)
    {
        if ($request->student_id != "") {
	       $student_id = $request->student_id;
	       return $this->payment($student_id);
	    }
    }
    public function payment($student_id){

    	
    	$studentDetails = $this->student_details($student_id)->first();
    	$schoolFees = $this->show_school_fee($studentDetails->level_id)->first();
    	$studentFees = $this->read_student_fee($student_id)->get(); 
    	$studentTransactions = $this->read_student_transaction($student_id)->get();
    	$receiptId = ReceiptDetail::where('student_id',$student_id)->max('receipt_id');
    	$programes  = Programe::where('programe_id',$studentDetails->programe_id)->get();
      $levels  = Level::where('level_id',$studentDetails->level_id)->get();
      $feeTypes = Feetype::all();

    
      $studentCheckBalance = StudentFee::where('student_id',$student_id)->orderBy('s_fee_id', 'DESC')->first();
      if (empty($studentCheckBalance)) {
        $balance = 0;
      } else {
        $balance = $studentCheckBalance->amount - $studentCheckBalance->total_paid;
      }


    	return compact('studentDetails','schoolFees','studentFees','studentTransactions','receiptId','programes','levels','feeTypes','balance');

    }
    public function student_details($student_id){
    	return Status::latest('statuses.status_id')
                         ->join('students','students.student_id','=','statuses.student_id')
                          ->join('courses','courses.course_id','=','statuses.course_id')
                          ->join('levels','levels.level_id','=','courses.level_id')
                          ->join('programes','programes.programe_id','=','levels.programe_id')
                          ->join('shifts','shifts.shift_id','=','courses.shift_id')
                          ->join('groups','groups.group_id','=','courses.group_id')
                          ->join('batches','batches.batch_id','=','courses.batch_id')
                          ->join('times','times.time_id','=','courses.time_id')
                          ->join('academics','academics.academic_id','=','courses.academic_id')
                          ->where('students.student_id',$student_id);
    }
    public function show_school_fee($level_id){
    	return Fee::join('academics','academics.academic_id','=','fees.academic_id')
                            ->join('levels','levels.level_id','=','fees.level_id')
                            ->join('feetypes','feetypes.fee_type_id','=','fees.fee_type_id')
                            ->join('programes','programes.programe_id','=','levels.programe_id')
                   			    ->where('levels.level_id',$level_id)
                            ->orderBy('fees.fee_id','desc');
    }
    public function read_student_fee($student_id){
        return StudentFee::join('fees','fees.fee_id','=','student_fees.fee_id')
                         ->join('students','students.student_id','=','student_fees.student_id')
                         ->join('levels','levels.level_id','=','student_fees.level_id')
                         ->join('programes','programes.programe_id','=','levels.programe_id')
                         ->select('levels.level_id',
                                  'levels.level',
                                  'programes.programe',
                                  'fees.amount as course_fee',
                                  'students.student_id',
                                  'student_fees.s_fee_id',
                                  'student_fees.amount as student_amount',
                                  'student_fees.discount',
                                  'student_fees.total_paid')
                         ->where('students.student_id', $student_id);
    }
    public function read_student_transaction($student_id){
        return ReceiptDetail::join('receipts','receipts.receipt_id','=','receipt_details.receipt_id')
                            ->join('students','students.student_id','=','receipt_details.student_id')
                            ->join('transactions','transactions.transact_id','=','receipt_details.transact_id')
                            ->join('fees','fees.fee_id','=','transactions.fee_id')
                            ->join('users','users.id','=','transactions.user_id')
                            ->join('student_fees','student_fees.s_fee_id','=','transactions.s_fee_id')
                            ->where('students.student_id', $student_id);
    }

    public function createFee(Request $request){
      return Fee::create([
            'academic_id' => $request['academic_id'],
            'level_id' => $request['level_id'],
            'fee_type_id' => $request['fee_type_id'],
            'fee_heading' => $request['fee_heading'],
            'amount' => $request['amount'],
        ]);
    }

    public function savePayment(Request $request){

        $studentFee = StudentFee::create([
            'fee_id' => $request['fee_id'],
            'student_id' => $request['student_id'],
            'level_id' => $request['level_id'],
            'amount' => $request['amount'],
            'discount' => $request['discount'],
            'total_paid' => $request['paid'],
        ]);
        $transact = Transaction::create(['transact_date' => Carbon::now(),
                                         'fee_id'=>$request->fee_id,
                                         'user_id'=>$request->user_id,
                                         'student_id'=>$request->student_id,
                                         's_fee_id'=>$studentFee->s_fee_id,
                                         'paid'=>$request->paid,
                                         'remark'=>$request->remark,
                                         'description'=>$request->description]);

        $receipt_id = Receipt::autoNumber();

        ReceiptDetail::create(['receipt_id'=>$receipt_id,
                                'student_id'=>$request->student_id,
                                'transact_id'=>$transact->transact_id]);

        
    }
    public function getExtraPay(Request $request){

      
        $studentFee = StudentFee::join('levels','levels.level_id','=','student_fees.level_id')
                                ->join('programes','programes.programe_id','=','levels.programe_id')
                                ->join('fees','fees.fee_id','=','student_fees.fee_id')
                                ->join('students','students.student_id','=','student_fees.student_id')
                                ->select('levels.level_id',
                                        'programes.programe_id',
                                        'fees.fee_id',
                                        'students.student_id',
                                        'student_fees.s_fee_id',
                                        'fees.amount as school_fee',
                                        'student_fees.amount as student_amount',
                                        'student_fees.discount',
                                        'student_fees.total_paid')
                                ->where('student_fees.s_fee_id',$request->s_fee_id)
                                ->first();
        return response($studentFee);
     

    }
    public function extraPayment(Request $request){

        $transact = Transaction::create(['transact_date' => Carbon::now(),
                                         'fee_id'=>$request->fee_id,
                                         'user_id'=>$request->user_id,
                                         'student_id'=>$request->student_id,
                                         's_fee_id'=>$request->s_fee_id,
                                         'paid'=>$request->extraPaid,
                                         'remark'=>$request->remark,
                                         'description'=>$request->description]);
        $tpay = Transaction::where('s_fee_id',$request->s_fee_id)->sum('paid');

        $studentFee = StudentFee::find($request->s_fee_id);
        $studentFee->fee_id = $studentFee->fee_id;
        $studentFee->student_id = $studentFee->student_id;
        $studentFee->level_id = $studentFee->level_id;
        $studentFee->amount = $studentFee->amount;
        $studentFee->discount = $studentFee->discount;
        $studentFee->total_paid = $tpay;
        $studentFee->save();

        $transact_id = $transact->transact_id;
        $student_id = $transact->student_id;
        $receipt_id = Receipt::autoNumber();
        ReceiptDetail::create(['receipt_id'=>$receipt_id,
        					   'student_id'=>$student_id,
        					   'transact_id'=>$transact_id]);
          
    }


    public function printInvoice($receipt_id=null){

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
                    ->where('receipts.receipt_id',$receipt_id)
                    ->first();
        $courseDetails = Course::join('levels','levels.level_id','=','courses.level_id')
                            ->join('shifts','shifts.shift_id','=','courses.shift_id')
                            ->join('times','times.time_id','=','courses.time_id')
                            ->join('groups','groups.group_id','=','courses.group_id')
                            ->join('academics','academics.academic_id','=','courses.academic_id')
                            ->join('programes','programes.programe_id','=','levels.programe_id')
                            ->join('statuses','statuses.course_id','=','courses.course_id')
                            ->where('levels.level_id',$invoice->level_id)
                            ->where('statuses.student_id',$invoice->student_id)
                            ->select(DB::raw('CONCAT(programes.programe,
                                              " / Batch-",levels.level,
                                              " / Shift-",shifts.shift,
                                              " / Time-",times.time,
                                              " / Group-",groups.group,
                                              " / Academic-",academics.academic,
                                              " / Start Date-",courses.start_date,
                                              " / ",courses.end_date
                                              ) As detail'))
                            ->first();
        $studentFee = StudentFee::where('s_fee_id',$invoice->s_fee_id)->first();
        $totalPaid = Transaction::where('s_fee_id',$invoice->s_fee_id)->sum('paid');                    

     return view('backEnd.invoice.invoice',compact('invoice','courseDetails','studentFee','totalPaid'));
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

    public function getStudentFeeById(Request $request){
       return StudentFee::join('fees','fees.fee_id','=','student_fees.fee_id')
                         ->join('students','students.student_id','=','student_fees.student_id')
                         ->join('levels','levels.level_id','=','student_fees.level_id')
                         ->join('programes','programes.programe_id','=','levels.programe_id')
                         ->select('levels.level_id',
                                  'levels.level',
                                  'programes.programe_id',
                                  'programes.programe',
                                  'fees.amount as school_fee',
                                  'students.student_id',
                                  'fees.fee_id',
                                  'student_fees.s_fee_id',
                                  'student_fees.amount as student_amount',
                                  'student_fees.discount')
                         ->where('student_fees.s_fee_id',$request->s_fee_id)
                         ->first();
         
    }
    public function updateStudentFee(Request $request){
        if ($request->ajax()) {
          
            $studentFee = StudentFee::find($request->s_fee_id);
            $studentFee->fee_id = $request->fee_id;
            $studentFee->student_id = $request->student_id;
            $studentFee->level_id = $request->level_id;
            $studentFee->amount = $request->student_amount;
            $studentFee->discount = $request->discount;
            $studentFee->save();
        }   
    }
    public function deleteStudentFee($s_fee_id){
    	Transaction::where('s_fee_id',$s_fee_id)->delete();
      StudentFee::destroy($s_fee_id);
    }

    // payment history
    /*public function getPaymentHistory(){
     
            $studentFee = StudentFee::join('fees','fees.fee_id','=','student_fees.fee_id')
                             ->join('students','students.student_id','=','student_fees.student_id')
                             ->join('levels','levels.level_id','=','student_fees.level_id')
                             ->join('programes','programes.programe_id','=','levels.programe_id')
                             ->join('feetypes','feetypes.fee_type_id','=','fees.fee_type_id')
                             ->select(
                                  'student_fees.student_id',
                                  'students.name as studentName',
                                  'students.nick_name',
                                  'programes.programe',
                                  'levels.level',
                                  'feetypes.fee_type',
                                  'student_fees.amount as studentAmount',
                                  'student_fees.s_fee_id',
                                  'student_fees.discount',
                                  'student_fees.total_paid',
                                  'fees.amount as courseFee'
                                )
                            ->orderBy('student_fees.student_id')
                            ->get();
              
             $transact = Transaction::get();
             return view('payment.paymentHistory',compact('studentFee','transact'));
             // return $studentFee;
            
    }*/
    public function getPaymentHistory(){
     
            $studentss = Student::join('student_fees','student_fees.student_id','=','students.student_id')
                             ->join('fees','fees.fee_id','=','student_fees.fee_id')
                             ->select(
                                  'student_fees.student_id',
                                  'students.name',
                                  'students.nick_name',
                                  'student_fees.amount as studentFee',
                                  'student_fees.discount',
                                  'student_fees.total_paid',
                                  'fees.amount as courseFee'
                                )
                            ->orderBy('students.student_id')
                            ->get();

            

            $fstudents = Student::select('student_id','name','nick_name')->get();
            $fstudentFees = StudentFee::select('student_id','amount','total_paid')->get();
            $total_paid = 0;
            
            foreach ($fstudents as $key => $fstudent) {
                  $students[] = [
                                'student_id'=>$fstudent['student_id'],
                                'name'=>$fstudent['name'],
                                'nick_name'=>$fstudent['nick_name'],
                              ];

            }
            // dd($fstudentFees);




            $studentFee = StudentFee::join('fees','fees.fee_id','=','student_fees.fee_id')
                             ->join('students','students.student_id','=','student_fees.student_id')
                             ->join('levels','levels.level_id','=','student_fees.level_id')
                             ->join('programes','programes.programe_id','=','levels.programe_id')
                             ->join('feetypes','feetypes.fee_type_id','=','fees.fee_type_id')
                             ->select(
                                  'student_fees.student_id',
                                  'students.name as studentName',
                                  'students.nick_name',
                                  'programes.programe',
                                  'levels.level',
                                  'student_fees.amount as studentAmount',
                                  'student_fees.discount',
                                  'student_fees.total_paid',
                                  'student_fees.s_fee_id',
                                  'fees.amount as courseFee'
                                )
                            ->orderBy('student_fees.student_id')
                            ->get();
              
             $transaction = ReceiptDetail::join('receipts','receipts.receipt_id','=','receipt_details.receipt_id')
                            ->join('students','students.student_id','=','receipt_details.student_id')
                            ->join('transactions','transactions.transact_id','=','receipt_details.transact_id')
                            ->join('fees','fees.fee_id','=','transactions.fee_id')
                            ->join('users','users.id','=','transactions.user_id')
                            ->join('student_fees','student_fees.s_fee_id','=','transactions.s_fee_id')
                            ->select(
                                      'users.name',
                                      'receipts.receipt_id',
                                      'transactions.s_fee_id',
                                      'transactions.transact_date',
                                      'transactions.paid',
                                      'transactions.remark',
                                      'transactions.description'
                            )->get();
    
    
             return compact('students','studentFee','transaction');
            
    }

    
    public function globalSearchStudentPayment(){
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
             return view('backEnd.payment.paymentHistory',compact('studentFee','transact'));

        }
    }


    public function getTransactionById(Request $request){
       return ReceiptDetail::join('receipts','receipts.receipt_id','=','receipt_details.receipt_id')
                            ->join('transactions','transactions.transact_id','=','receipt_details.transact_id')
                            ->join('fees','fees.fee_id','=','transactions.fee_id')
                            ->join('users','users.id','=','transactions.user_id')
                            ->join('student_fees','student_fees.s_fee_id','=','transactions.s_fee_id')
                            ->select('transactions.transact_date',
                                  'transactions.transact_id',
                                  'transactions.fee_id',
                                  'transactions.user_id',
                                  'users.name',
                                  'transactions.student_id',
                                  'transactions.s_fee_id',
                                  'transactions.paid',
                                  'transactions.remark',
                                  'transactions.description')
                            ->where('transactions.transact_id', $request->transact_id)
                            ->first();
         
    }

    public function getTransactionHistory(){
     
            $transactions=  Transaction::join('receipt_details','receipt_details.transact_id','=','transactions.transact_id')
                            ->join('fees','fees.fee_id','=','transactions.fee_id')
                            ->join('feetypes','feetypes.fee_type_id','=','fees.fee_type_id')
                            ->join('users','users.id','=','transactions.user_id')
                            ->join('students','students.student_id','=','transactions.student_id')
                            ->select('transactions.transact_date',
                                  'transactions.transact_id',
                                  'transactions.student_id',
                                  'feetypes.fee_type',
                                  'fees.fee_heading as feeType',
                                  'users.name as cashier',
                                  'students.name as studentName',
                                  'transactions.paid')
                            ->orderBy('transactions.transact_date')
                            ->get();
          return view('backEnd.payment.transactionHistory',compact('transactions'));
      
    }
    
    public function globalSearchTransaction(){
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') || \Gate::allows('isUser') ) {

            if ($search = \Request::get('q')) {
                $transactions = Transaction::join('receipt_details','receipt_details.transact_id','=','transactions.transact_id')
                          ->join('fees','fees.fee_id','=','transactions.fee_id')
                          ->join('feetypes','feetypes.fee_type_id','=','fees.fee_type_id')
                          ->join('users','users.id','=','transactions.user_id')
                          ->join('students','students.student_id','=','transactions.student_id')
                          ->select('transactions.transact_date',
                                  'transactions.transact_id',
                                  'transactions.student_id',
                                  'feetypes.fee_type',
                                  'fees.fee_heading as feeType',
                                  'users.name as cashier',
                                  'students.name as studentName',
                                  'transactions.paid')
                          ->where(function($query) use ($search){
                                $query->where('students.name', 'LIKE', "%$search%")
                                      ->orWhere('students.nick_name', 'LIKE', "%$search%")
                                      ->orWhere('transactions.transact_date', 'LIKE', "%$search%")
                                      ->orWhere('transactions.transact_id', 'LIKE', "%$search%")
                                      ->orWhere('fees.fee_heading', 'LIKE', "%$search%")
                                      ->orWhere('students.email', 'LIKE', "%$search%")
                                      ->orWhere('students.student_id','LIKE', "%$search%");
                          })->get();
            } else{
                $transactions = Transaction::join('receipt_details','receipt_details.transact_id','=','transactions.transact_id')
                            ->join('fees','fees.fee_id','=','transactions.fee_id')
                            ->join('feetypes','feetypes.fee_type_id','=','fees.fee_type_id')
                            ->join('users','users.id','=','transactions.user_id')
                            ->join('students','students.student_id','=','transactions.student_id')
                            ->select('transactions.transact_date',
                                  'transactions.transact_id',
                                  'transactions.student_id',
                                  'feetypes.fee_type',
                                  'fees.fee_heading as feeType',
                                  'users.name as cashier',
                                  'students.name as studentName',
                                  'transactions.paid')
                            ->get();
            }

            return view('backEnd.payment.transactionHistory',compact('transactions'));

        }
    }
    

    public function updateTransaction(Request $request){
        if ($request->ajax()) {
          
            $transaction = Transaction::find($request->transact_id);
            $transaction->transact_date = $request->transact_date;
            $transaction->fee_id = $request->fee_id;
            $transaction->user_id = $request->user_id;
            $transaction->student_id = $request->student_id;
            $transaction->s_fee_id = $request->s_fee_id;
            $transaction->paid = $request->paid;
            $transaction->remark = $request->remark;
            $transaction->description = $request->description;
            $transaction->save();
        $tpay = Transaction::where('s_fee_id',$request->s_fee_id)->sum('paid');

        $studentFee = StudentFee::find($request->s_fee_id);
        $studentFee->fee_id = $studentFee->fee_id;
        $studentFee->student_id = $studentFee->student_id;
        $studentFee->level_id = $studentFee->level_id;
        $studentFee->amount = $studentFee->amount;
        $studentFee->discount = $studentFee->discount;
        $studentFee->total_paid = $tpay;
        $studentFee->save();
      }
    }
    public function deleteTransaction($transact_id){
        $transact = Transaction::findOrFail($transact_id);
        $transact->delete();
        $tpay = Transaction::where('s_fee_id',$transact->s_fee_id)->sum('paid');

        $studentFee = StudentFee::find($transact->s_fee_id);
        $studentFee->fee_id = $studentFee->fee_id;
        $studentFee->student_id = $studentFee->student_id;
        $studentFee->level_id = $studentFee->level_id;
        $studentFee->amount = $studentFee->amount;
        $studentFee->discount = $studentFee->discount;
        $studentFee->total_paid = $tpay;
        $studentFee->save();
    }


}
