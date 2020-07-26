<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Academic;
use App\Model\Programe;
use App\Model\Level;
use App\Model\Shift;
use App\Model\Time;
use App\Model\Batch;
use App\Model\Group;
use App\Model\Course;
use App\Model\Status;
use App\Model\Fee;

class CourseController extends Controller
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
     * for Academic CRUD
     * @return void
     */
    
	public function loadAcademicsData()
    {
    	// $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') ) {
       		return Academic::orderBy('academic_id', 'DESC')->get();
       	}
    }

    public function addAcademicYear(Request $request)
    {

    	$this->validate($request,[
            'academic' => 'required|max:9|unique:academics'
        ]);
        return Academic::create([
            'academic' => $request['academic'],
        ]);

    }

    /**
     * for Programe CRUD
     * @return void
     */

    public function loadProgramesData()
    {
        // $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') ) {
            return Programe::orderBy('programe', 'ASC')->get();
        }
    }

    public function addPrograme(Request $request)
    {
        $this->validate($request,[
            'programe' => 'required|max:15|unique:programes',
            'description' => 'required|max:50'
        ]);
        return Programe::create([
            'programe' => $request['programe'],
            'description' => $request['description'],
        ]);
    }

    /**
     * for Level CRUD
     * @return void
     */

    public function loadLevelsData($request)
    {
        // $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') ) {
            return Level::where('programe_id',$request)->get();
        }
    }

    public function addLevel(Request $request)
    {
        // dd($request['level']['programe_id']);
        $this->validate($request,[
            'programe_id' => 'required',
            'level' => 'required|max:15',
            'description' => 'required|max:50'
        ]);
        return Level::create([
            'programe_id' => $request['programe_id'],
            'level' => $request['level'],
            'description' => $request['description'],
        ]);
    }

    /**
     * for Shift CRUD
     * @return void
     */
    
    public function loadShiftsData()
    {
        // $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') ) {
            return Shift::all();
        }
    }

    public function addShift(Request $request)
    {
        // dd($request['shift']['shift']);
        $this->validate($request,[
            'shift' => 'required|max:15|unique:shifts',
            'description' => 'required|max:50'
        ]);
        return Shift::create([
            'shift' => $request['shift'],
            'description' => $request['description'],
        ]);

    }

    /**
     * for Time CRUD
     * @return void
     */
    
    public function loadTimesData()
    {
        // $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') ) {
            return Time::all();
        }
    }

    public function addTime(Request $request)
    {
        // dd($request['shift']['shift']);
        $this->validate($request,[
            'time' => 'required|max:15|unique:times',
            'description' => 'required|max:50'
        ]);
        return Time::create([
            'time' => $request['time'],
            'description' => $request['description'],
        ]);

    }

    /**
     * for Batch CRUD
     * @return void
     */
    
    public function loadBatchesData()
    {
        // $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') ) {
            return Batch::all();
        }
    }

    public function addBatch(Request $request)
    {
        // dd($request['shift']['shift']);
        $this->validate($request,[
            'batch' => 'required|max:15|unique:batches',
            'description' => 'required|max:50'
        ]);
        return Batch::create([
            'batch' => $request['batch'],
            'description' => $request['description'],
        ]);

    }
    /**
     * for Group CRUD
     * @return void
     */
    
    public function loadGroupsData()
    {
        // $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') ) {
            return Group::all();
        }
    }

    public function addGroup(Request $request)
    {
        // dd($request['shift']['shift']);
        $this->validate($request,[
            'group' => 'required|max:15|unique:groups',
            'description' => 'required|max:50'
        ]);
        return Group::create([
            'group' => $request['group'],
            'description' => $request['description'],
        ]);

    }

    /**
     * for Course CRUD
     * @return void
     */
    
    public function loadCourses(){
        
        return Course::join('academics','academics.academic_id','=','courses.academic_id')
                      ->join('levels','levels.level_id','=','courses.level_id')
                      ->join('programes','programes.programe_id','=','levels.programe_id')
                      ->join('shifts','shifts.shift_id','=','courses.shift_id')
                      ->join('times','times.time_id','=','courses.time_id')
                      ->join('groups','groups.group_id','=','courses.group_id')
                      ->join('batches','batches.batch_id','=','courses.batch_id')
                      ->orderBy('courses.course_id','DESC')
                      ->latest('courses.created_at')
                      ->paginate(5);
    }
    
    public function loadCourseById(Request $request){
      
         return Course::join('levels','levels.level_id','=','courses.level_id')
                      ->join('programes','programes.programe_id','=','levels.programe_id')
                      ->find($request['id']);
        
         
    }

    

    public function addCourse(Request $request)
    {
        // dd($request['course']);
        
        $this->validate($request,[
            'academic_id' => 'required',
            'level_id' => 'required',
            'shift_id' => 'required',
            'time_id' => 'required',
            'batch_id' => 'required',
            'group_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'required|max:50',
        ]);
        return Course::create([
            'academic_id' => $request['academic_id'],
            'level_id' => $request['level_id'],
            'shift_id' => $request['shift_id'],
            'time_id' => $request['time_id'],
            'batch_id' => $request['batch_id'],
            'group_id' => $request['group_id'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'active' => 1,
            'description' => $request['description'],
        ]);

    }

    public function addAnotherNewCourse(Request $request)
    {

        $status = Status::create([
            'student_id' => $request['student_id'],
            'course_id' => $request['course_id'],
        ]);
        
        $studentDetails = Status::latest('statuses.status_id')
                         ->join('students','students.student_id','=','statuses.student_id')
                          ->join('courses','courses.course_id','=','statuses.course_id')
                          ->join('levels','levels.level_id','=','courses.level_id')
                          ->join('programes','programes.programe_id','=','levels.programe_id')
                          ->join('shifts','shifts.shift_id','=','courses.shift_id')
                          ->join('groups','groups.group_id','=','courses.group_id')
                          ->join('batches','batches.batch_id','=','courses.batch_id')
                          ->join('times','times.time_id','=','courses.time_id')
                          ->join('academics','academics.academic_id','=','courses.academic_id')
                          ->where('students.student_id',$status->student_id)
                          ->first();
        $schoolFees = Fee::join('academics','academics.academic_id','=','fees.academic_id')
                            ->join('levels','levels.level_id','=','fees.level_id')
                            ->join('feetypes','feetypes.fee_type_id','=','fees.fee_type_id')
                            ->join('programes','programes.programe_id','=','levels.programe_id')
                            ->where('levels.level_id',$studentDetails->level_id)
                            ->orderBy('fees.fee_id','desc')
                            ->first();
        
        $programes  = Programe::where('programe_id',$studentDetails->programe_id)->get();
        $levels  = Level::where('level_id',$studentDetails->level_id)->get();
        return compact('studentDetails','programes','levels','schoolFees');

    }



    
    public function updateCourse(Request $request, $id)
    {
        
        $this->validate($request,[
            'academic_id' => 'required',
            'level_id' => 'required',
            'shift_id' => 'required',
            'time_id' => 'required',
            'batch_id' => 'required',
            'group_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'required|max:50',
        ]);
        return Course::updateOrCreate(['course_id'=>$id],[
            'academic_id' => $request['academic_id'],
            'level_id' => $request['level_id'],
            'shift_id' => $request['shift_id'],
            'time_id' => $request['time_id'],
            'batch_id' => $request['batch_id'],
            'group_id' => $request['group_id'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'active' => 1,
            'description' => $request['description'],
        ]);
                
        
    }

    public function deleteCourse($id){
      if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') ) {
            $course = Course::findOrFail($id);
            $course->delete();
            return $course;
      }
        
        
    }

    public function searchCourses(Request $request){
     
        // dd($request->programe_id);
        if ($request->academic_id != "") {

            $criterial = array('academics.academic_id'=>$request->academic_id);

        }
        if ($request->academic_id != "" && $request->programe_id != "") {

            $criterial = array(
                                'academics.academic_id'=>$request->academic_id,
                                'programes.programe_id'=>$request->programe_id,
                              );

        } 
        
        if ($request->academic_id != "" && 
            $request->programe_id != "" &&
            $request->level_id != "" &&
            $request->shift_id != "" &&
            $request->time_id != "" &&
            $request->batch_id != "" &&
            $request->group_id != "") {
            $criterial = array(
                                'academics.academic_id'=>$request->academic_id,
                                'programes.programe_id'=>$request->programe_id,
                                'levels.level_id'=>$request->level_id,
                                'shifts.shift_id'=>$request->shift_id,
                                'times.time_id'=>$request->time_id,
                                'batches.batch_id'=>$request->batch_id,
                                'groups.group_id'=>$request->group_id
                            );
        }

       
      return  $courses = $this->showCourses($criterial)->get();
        
    }

     public function showCourses($criterial){
        
        return Course::join('academics','academics.academic_id','=','courses.academic_id')
                      ->join('levels','levels.level_id','=','courses.level_id')
                      ->join('programes','programes.programe_id','=','levels.programe_id')
                      ->join('shifts','shifts.shift_id','=','courses.shift_id')
                      ->join('times','times.time_id','=','courses.time_id')
                      ->join('groups','groups.group_id','=','courses.group_id')
                      ->join('batches','batches.batch_id','=','courses.batch_id')
                      ->where($criterial)
                      ->where('courses.active',1)
                      ->orderBy('courses.course_id','DESC');
    }



    










}
