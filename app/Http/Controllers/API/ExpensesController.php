<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Expenses;
use App\Model\DailyExpense;
use App\Model\ExpenseTypes;
use Carbon\Carbon;

class ExpensesController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenseTypes = ExpenseTypes::all();
        $expenses = Expenses::latest('date')->paginate(10);
        $dailyExpenses = DailyExpense::join('users','users.id','=','daily_expense.user_id')
                                     ->join('expense_types','expense_types.e_type_id','=','daily_expense.e_type_id')
                                     ->select(
                                        'daily_expense.d_e_id',
                                        'daily_expense.expense_id',
                                        'daily_expense.date',
                                        'daily_expense.description',
                                        'daily_expense.amount',
                                        'users.type as userType',
                                        'expense_types.expense_type as expenseType'
                                     )->get();
        return compact('expenseTypes','expenses','dailyExpenses');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'e_type_id' => 'required',            
            'description' => 'required|string|max:80',            
            'amount' => 'required'            
        ]);

        $existsExpenseDate = $this->ExistsExpenseDate($request->date);
        // dd($existsExpenseDate);
        // exit();
        if ($existsExpenseDate==false) {
            $expense = Expenses::create([ 'date'=> $request->date,
                                          'total_expense'=>$request->amount]);
            $dailyExpense = DailyExpense::create([ 'expense_id'=>$expense->expense_id,
                                      'user_id'=>$request->user_id,
                                      'e_type_id'=>$request->e_type_id,
                                      'date' => Carbon::now(),
                                      'description'=>$request->description,
                                      'amount'=>$request->amount]);
        } else {
            $expenseId = $this->ExpenseId($request->date);
            $dailyExpense = DailyExpense::create([ 'expense_id'=>$expenseId,
                                      'user_id'=>$request->user_id,
                                      'e_type_id'=>$request->e_type_id,
                                      'date' => Carbon::now(),
                                      'description'=>$request->description,
                                      'amount'=>$request->amount]);
            $totalExpense = DailyExpense::where('expense_id',$dailyExpense->expense_id)->sum('amount');
            $upexpense = Expenses::find($dailyExpense->expense_id);
            $upexpense->date = $upexpense->date;
            $upexpense->total_expense = $totalExpense;
            $upexpense->save();

        }
    }

    public function ExistsExpenseDate($date)
    {
        $existsExpense = Expenses::where(function($query) use ($date){
                           $query->where('date', 'LIKE', "%$date%");
                         })
                       ->first();
        if (empty($existsExpense)) {
            return false;
        } else {
            return true;
        }
    }

    public function ExpenseId($date)
    {
        $expenseId = Expenses::where(function($query) use ($date){
                                $query->where('date', 'LIKE', "%$date%");
                               })
                               ->first();
        return $expenseId->expense_id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return DailyExpense::find($id);

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
            $dailyExpense = DailyExpense::find($id);
            $dailyExpense->expense_id = $request->expense_id;
            $dailyExpense->user_id = $request->user_id;
            $dailyExpense->e_type_id = $request->e_type_id;
            $dailyExpense->date = $request->date;
            $dailyExpense->description = $request->description;
            $dailyExpense->amount = $request->amount;
            $dailyExpense->save();

            $totalExpense = DailyExpense::where('expense_id',$request->expense_id)->sum('amount');
            $upexpense = Expenses::find($request->expense_id);
            $upexpense->date = $upexpense->date;
            $upexpense->total_expense = $totalExpense;
            $upexpense->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $dailyExpense = DailyExpense::findOrFail($id);
        $dailyExpense->delete();
        
        $totalExpense = DailyExpense::where('expense_id',$dailyExpense->expense_id)->sum('amount');
        $upexpense = Expenses::find($dailyExpense->expense_id);
        $upexpense->date = $upexpense->date;
        $upexpense->total_expense = $totalExpense;
        $upexpense->save();
    }
    public function expenseDelete($expense_id)
    {
        $this->authorize('isAdmin');
        DailyExpense::where('expense_id',$expense_id)->delete();
        Expenses::destroy($expense_id);
    }
}
