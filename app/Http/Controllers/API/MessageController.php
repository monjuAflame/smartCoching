<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Student;
use App\Model\StudentMessage;
use App\Model\Status;
use App\Model\Academic;
use App\Model\level;
use App\Model\Programe;
use App\User;
use Carbon\Carbon;
use SmsTo;
use Auth;
use DB;
class MessageController extends Controller
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
        $students = Student::select('id','student_id','name','nick_name','phone')
                        ->latest()
                        ->paginate(2);
        $academics = Academic::select('academic_id','academic')->get();
        $programes = Programe::select('programe_id','programe')->get();
        $levels = level::select('level_id','level')->get();
        $messages = StudentMessage::latest('send_date')->paginate(2);
        return compact('students','academics','programes','levels','messages');
    }

    public function searchStudentByProgrameAcademic(Request $request){
         // dd($request->programe_id);
        if ($request->academic_id != "") {

            $criterial = array('academics.academic_id'=>$request->academic_id);

        }
        if ($request->programe_id != "") {

            $criterial = array('programes.programe_id'=>$request->programe_id);

        } 
        if ($request->academic_id != "" && $request->programe_id != "") {
            $criterial = array(
                                'academics.academic_id'=>$request->academic_id,
                                'programes.programe_id'=>$request->programe_id
                            );
        }
        if ($request->programe_id != "" && $request->level_id != "") {
            $criterial = array(
                                'programes.programe_id'=>$request->programe_id,
                                'levels.level_id'=>$request->level_id
                            );
        }
        if ($request->academic_id != "" && $request->programe_id != "" && $request->level_id != "") {
            $criterial = array(
                                'academics.academic_id'=>$request->academic_id,
                                'programes.programe_id'=>$request->programe_id,
                                'levels.level_id'=>$request->level_id
                            );
        }

        
        
      return  $students = $this->showStudent($criterial)->get();
    }
    public function showStudent($criterial){
        
        return Student::join('student_as_programe_and_academic_years','student_as_programe_and_academic_years.student_id','=','students.student_id')
                    ->join('academics','academics.academic_id','=','student_as_programe_and_academic_years.academic_id')
                    ->join('levels','levels.level_id','=','student_as_programe_and_academic_years.level_id')
                      ->join('programes','programes.programe_id','=','levels.programe_id')
                      ->where($criterial)
                      ->where('students.active',1)
                      ->orderBy('students.student_id','DESC');
    }
    public function sendMessage(Request $request){
            $this->validate($request,[
            'message'       => 'required'
        ]);
            // dd($request->input('mobile_number.*.phone'));
            $message = $request->input('message');
            $mobile = $request->input('mobile_number.*.phone');
            // $encodeMessage = urlencode($message);
            // $postData = $request->all();

            //Multiple mobiles numbers separated by comma
            $mobileNumber = implode('', $mobile);
            $arr = str_split($mobileNumber,'11');
            $mobiles = implode(',', $arr);

            // print_r($mobiles);
            // exit();

            StudentMessage::create([
                    'user_id'=> Auth::user()->id,
                    'message'=> $message,
                    'mobiles'=> $mobiles,
                    'send_date'=> Carbon::now(),
            ]);

            $response = SmsTo::setMessage($message)
                             ->setRecipients($mobiles)
                             ->setSenderId('Admin')
                             ->setCallbackUrl('https://smartcoching.com/sms')
                             ->sendMultiple();

            if ($response['success'] == true) {
              return $response;
            } else {
              return $response['errors'];
            }















           /*
            //Your authentication key
            $authKey = '';
            //Multiple mobiles numbers separated by comma
            // $mobileNumber = "9999999";

            //Sender ID,While using route4 sender id should be 6 characters long.
            $senderId = "";

            //Define route 
            $route = "default";

            //Prepare you post parameters
            $postData = array(
                'authkey' => $authKey,
                'mobiles' => $mobiles,
                'message' => $encodeMessage,
                'sender' => $senderId,
                'route' => $route
            );

            //API URL
            $url="http://api.msg91.com/api/sendhttp.php";

            // init the resource
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postData
                //,CURLOPT_FOLLOWLOCATION => true
            ));


            //Ignore SSL certificate verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


            //get response
            $output = curl_exec($ch);

            //Print error if any
            if(curl_errno($ch))
            {
                echo 'error:' . curl_error($ch);
            }

            curl_close($ch);
            /*if ($output) {
                return StudentMessage::create([
                    'user_id'=> Auth::user()->id,
                    'message'=> $encodeMessage,
                    'mobiles'=> $mobiles,
                    'send_date'=> Carbon::now(),
                ]);
            }
            return $output;
          */

    }
    public function getStudentByCourse($course_id){

        return Status::join('students','students.student_id','=','statuses.student_id')
                     ->join('courses','courses.course_id','=','statuses.course_id')
                     ->where('courses.course_id',$course_id)
                     ->paginate(20);


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
        return StudentMessage::find($id);
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
        $studentMessage = StudentMessage::find($id);
        $studentMessage->delete();
    }
}
