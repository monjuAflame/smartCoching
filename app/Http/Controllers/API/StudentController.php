<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Model\Student;
use App\Model\Status;
use App\Model\Course;
use App\Model\StudentAsProgrameAndAcademicYear;
use App\Model\StudentFee;
use App\Model\Transaction;



class StudentController extends Controller
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
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') ) {
            return Student::latest()->paginate(10);
        }
    }


    public function globalSearchStudent(){
       if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') ) {

            if ($search = \Request::get('q')) {
                $students = Student::where(function($query) use ($search){
                    $query->where('name', 'LIKE', "%$search%")
                          ->orWhere('nick_name', 'LIKE', "%$search%")
                          ->orWhere('student_id', 'LIKE', "%$search%")
                          ->orWhere('email', 'LIKE', "%$search%")
                          ->orWhere('district','LIKE', "%$search%")
                          ->orWhere('updistrict','LIKE', "%$search%")
                          ->orWhere('phone','LIKE', "%$search%");
                })->paginate(10);
            } else{
                $students = Student::latest()->paginate(10);
            }

            return $students;

        }
    }
    public function searchStudent($student_id){
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') || \Gate::allows('isUser') ) {

            if (empty($student_id)) {
                $students = Student::latest()->paginate(10);
            } else{
                $students = Student::where(function($query) use ($student_id){
                    $query->where('student_id', 'LIKE', "%$student_id%");
                })->paginate(10);
            }

            return $students;

        }
    }


    public function getMaxStudentId(){
       if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') ) {
            return Student::max('student_id');
        }
    }


    public function getStudentByIdForViewStudent($student_id){
       if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor') ) {
            return Student::join('statuses','statuses.student_id','=','students.student_id')
                            ->join('courses','courses.course_id','=','statuses.course_id')
                            ->join('levels','levels.level_id','=','courses.level_id')
                            ->join('programes','programes.programe_id','=','levels.programe_id')
                            ->join('shifts','shifts.shift_id','=','courses.shift_id')
                            ->join('groups','groups.group_id','=','courses.group_id')
                            ->join('batches','batches.batch_id','=','courses.batch_id')
                            ->join('times','times.time_id','=','courses.time_id')
                            ->join('academics','academics.academic_id','=','courses.academic_id')
                            ->where('students.student_id',$student_id)
                            ->select(\DB::raw('students.id,students.student_id,students.user_id,students.name,
                                            students.nick_name,students.dob,students.national_id,students.sex,
                                            students.status,students.nationality,students.zipcode,
                                            students.passport,students.email,students.phone,students.photo,
                                            students.active,students.village,students.post_office,
                                            students.updistrict,students.district,students.current_address,
                                            students.description,students.dateregistered,statuses.course_id,
                                            statuses.status_id,programes.programe,levels.level,batches.batch,
                                            shifts.shift,groups.group,times.time,academics.academic,
                                            courses.start_date,courses.end_date'))
                            ->first();
        }
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
            'course_id'       => 'required',
            'name'            => 'required|string|max:50',
            'nick_name'       => 'required|string|max:15',
            'dob'             => 'required',
            'sex'             => 'required',
            'status'          => 'required',
            'national_id'     => 'unique:students|max:30',
            'nationality'     => 'required|string|max:25',
            'passport'        => 'max:30|unique:students',
            'zipcode'         => 'max:10',
            'email'           => 'max:100|unique:students',
            'phone'           => 'required|string|max:15|unique:students',
            'village'         => 'required|string|max:25',
            'post_office'     => 'required|string|max:25',
            'updistrict'      => 'required|string|max:25',
            'district'        => 'required|string|max:25',
            'current_address' => 'string|max:100',
            'description'     => 'max:50'
        ]);

        if ($request->photo != '') {

            $name = time().'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            \Image::make($request->photo)->save(public_path('img/student/').$name);

            // $request->photo = $name;
            $request->merge(['photo'=>$name]);
        }

        $student = new Student;
        
        $student->user_id = $request->user_id;
        $student->student_id = $request->student_id;
        $student->name = $request->name;
        $student->nick_name = $request->nick_name;
        $student->sex = $request->sex;
        $student->dob = $request->dob;
        $student->email = $request->email;
        $student->zipcode = $request->zipcode;
        $student->status = $request->status;
        $student->nationality = $request->nationality;
        $student->national_id = $request->national_id;
        $student->passport = $request->passport;
        $student->phone = $request->phone;
        $student->village = $request->village;
        $student->post_office = $request->post_office;
        $student->updistrict = $request->updistrict;
        $student->district = $request->district;
        $student->current_address = $request->current_address;
        $student->dateregistered = Carbon::now();
        $student->photo = $request->photo;
        $student->description = $request->description;
        $student->active = 1;
        
        if($student->save()){

           $student_id = $student->student_id;
           Status::insert(['student_id'=>$student_id,'course_id'=>$request->course_id]);
           $course = Course::find($request->course_id);
           StudentAsProgrameAndAcademicYear::insert([
                            'student_id'=>$student_id,
                            'academic_id'=>$course->academic_id,
                            'level_id'=>$course->level_id]);
           return $student_id;
        }





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
        // dd($request->all());

            $currentPhoto = $this->photoExistsStatus($id);

            if ($request->photo != $currentPhoto) {

                $name = time().'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
                \Image::make($request->photo)->save(public_path('img/student/').$name);

                // $request->photo = $name;
                $request->merge(['photo'=>$name]);

                $studentPhoto = public_path('img/student/').$currentPhoto;
                if (file_exists($studentPhoto)) {
                    @unlink($studentPhoto);
                }


            }
            
        
            $student = Student::findOrFail($id);
            $student->user_id = $request->user_id;
            $student->student_id = $request->student_id;
            $student->name = $request->name;
            $student->nick_name = $request->nick_name;
            $student->sex = $request->sex;
            $student->dob = $request->dob;
            $student->email = $request->email;
            $student->zipcode = $request->zipcode;
            $student->status = $request->status;
            $student->nationality = $request->nationality;
            $student->national_id = $request->national_id;
            $student->passport = $request->passport;
            $student->phone = $request->phone;
            $student->village = $request->village;
            $student->post_office = $request->post_office;
            $student->updistrict = $request->updistrict;
            $student->district = $request->district;
            $student->current_address = $request->current_address;
            $student->dateregistered = $request->dateregistered;
            $student->photo = $request->photo;
            $student->description = $request->description;
            $student->active = $request->active;

            if($student->save()){

                $status = Status::find($request->status_id);
                $status->student_id = $request->student_id;
                $status->course_id = $request->course_id;
                $status->save();


            }


    }

    private function photoExistsStatus($id){
        $student = Student::find($id);
        return $student->photo;
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
        Status::where('student_id',$id)->delete();
        Transaction::where('student_id',$id)->delete();
        StudentFee::where('student_id',$id)->delete();
        Student::where('student_id',$id)->delete();
        
        
    }
}
