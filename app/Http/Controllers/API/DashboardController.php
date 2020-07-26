<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Model\Programe;
use App\Model\Level;
use App\Model\Student;
use App\Model\Fee;
use App\Model\StudentFee;
use App\Model\Expenses;
use App\User;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programes = Programe::get();
        $tprogrames = Programe::count();
        $levels = Level::get();
        $tusers = User::count();
        $users = User::select('name')->get();
        $tstudents = Student::where('active',1)->count();
        $lstudents = Student::take(8)->count();
        $students = Student::select('name','photo','dateregistered')
                           ->where('active',1)
                           ->orderBy('student_id','ASC')
                           ->latest()
                           ->take(8)
                           ->get();
        
        $totalcourseamount = Fee::sum('amount');
        $totalstuentamount = StudentFee::sum('amount');
        $totalstuentpaid = StudentFee::sum('total_paid');
        $totalExpenses = Expenses::sum('total_expense');
        return compact('tstudents','students','lstudents','programes','tprogrames','levels','tusers','users','totalstuentamount','totalstuentpaid','totalcourseamount','totalExpenses');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
