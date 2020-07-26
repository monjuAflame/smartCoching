<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Fee;
use App\Model\Academic;
use App\Model\Programe;
use App\Model\Level;
use App\Model\Feetype;
use App\Model\ExpenseTypes;

class SettingsController extends Controller
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

    public function index(){
    	$expenseTypes = ExpenseTypes::get();
    	$feeTypes = Feetype::get();
    	$fees = Fee::get();
    	$academics = Academic::get();
    	return compact('expenseTypes','feeTypes','fees','academics');
    }

    // expenses Type
    public function getExpenseType(){
    	return ExpenseTypes::get();
    }

    public function createExpenseType(Request $request){
    	 $this->validate($request,[
            'expense_type'       => 'required'
        ]);
    	ExpenseTypes::insert(['expense_type'=>$request->expense_type]);
    }
    public function findExpenseType($id){
    	return ExpenseTypes::find($id);
    }

    public function updateExpenseType(Request $request){
    	 $this->validate($request,[
            'expense_type'       => 'required'
        ]);
    	$expenseType = ExpenseTypes::find($request->e_type_id);
    	$expenseType->expense_type = $request->expense_type;
    	$expenseType->save();
    }

    public function deleteExpenseType($id){
    	
    	$expenseType = ExpenseTypes::find($id);
    	$expenseType->delete();
    }
    // fee Type
    public function getFeeType(){
    	return Feetype::get();
    }
    public function createFeeType(Request $request){
    	 $this->validate($request,[
            'fee_type'       => 'required'
        ]);
    	Feetype::insert(['fee_type'=>$request->fee_type]);
    }
    public function findFeeType($id){
    	return Feetype::find($id);
    }

    public function updateFeeType(Request $request){
    	 $this->validate($request,[
            'fee_type'       => 'required'
        ]);
    	$feeType = Feetype::find($request->fee_type_id);
    	$feeType->fee_type = $request->fee_type;
    	$feeType->save();
    }

    public function deleteFeeType($id){
    	
    	$feeType = Feetype::find($id);
    	$feeType->delete();
    }


    // Course fee
    public function getCourseFee(){
    	$fees = Fee::join('academics','academics.academic_id','=','fees.academic_id')
    				->join('levels','levels.level_id','=','fees.level_id')
    				->join('feetypes','feetypes.fee_type_id','=','fees.fee_type_id')
    				->join('programes','programes.programe_id','=','levels.programe_id')
    				->select('academics.academic',
    						 'academics.academic_id',
    					     'levels.level',
    					     'levels.level_id',
    					     'feetypes.fee_type',
    					     'feetypes.fee_type_id',
    					     'fees.amount',
    					     'fees.fee_id',
    					     'fees.fee_heading',
    					     'programes.programe_id',
    					     'programes.programe')
    				->get();
    	$academics = Academic::get();
    	$programes = Programe::get();
    	$levels = Level::get();
    	$feeTypes = Feetype::get();
    	return compact('fees','academics','programes','levels','feeTypes');
    }

    public function createCourseFee(Request $request){
    	 $this->validate($request,[
            'academic_id'       => 'required',
            'programe_id'       => 'required',
            'level_id'       => 'required',
            'fee_type_id'       => 'required',
            'amount'       => 'required'
        ]);
    	Fee::insert([
    		'academic_id'=>$request->academic_id,
    		'level_id'=>$request->level_id,
    		'fee_type_id'=>$request->fee_type_id,
    		'fee_heading'=>$request->fee_heading,
    		'amount'=>$request->amount,
    	]);
    }
    public function findCourseFee($id){
    	return Fee::join('levels','levels.level_id','=','fees.level_id')
    				->join('programes','programes.programe_id','=','levels.programe_id')
    				->where('fees.fee_id',$id)
    				->first();
    }

    public function updateCourseFee(Request $request){
    	 
    	$fees = Fee::find($request->fee_id);
    	$fees->academic_id = $request->academic_id;
    	$fees->level_id = $request->level_id;
    	$fees->fee_type_id = $request->fee_type_id;
    	$fees->fee_heading = $request->fee_heading;
    	$fees->amount = $request->amount;
    	$fees->save();
    }

    public function deleteCourseFee($id){
    	
    	$fees = Fee::find($id);
    	$fees->delete();
    }
























}
