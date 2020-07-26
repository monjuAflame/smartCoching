<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Model\ChatAdmin;
use Illuminate\Http\Request;
use App\User;

class ChatAdminController extends Controller
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
        
    }
    public function userList()
    {
        if (\Request::ajax()) {
            $users = User::latest()->where('id','!=',auth()->user()->id)->get();
            return response()->json($users,200);
        }
        return abort(404);
        
    }
    public function userMessage($id=null)
    {

        if (\Request::ajax()) {
            $user = User::findOrFail($id);
            $userMessage = $this->messageByUserId($id);
            return response()->json([
                'messages'=>$userMessage,
                'user'=>$user
            ]);
        }
        return abort(404);
        
    }

    
    public function saveMessage(Request $request)
    {
        if (\Request::ajax()) {
            $message = ChatAdmin::create([
                'message'=>$request->message,
                'from'=>auth()->user()->id,
                'to'=>$request->user_id,
                'type'=>0
            ]);
            $message = ChatAdmin::create([
                'message'=>$request->message,
                'from'=>auth()->user()->id,
                'to'=>$request->user_id,
                'type'=>1
            ]);
            return response()->json($message,200);
        }
        return abort(404);

    }

    public function deleteSingleMessage($id)
    {
        if (\Request::ajax()) {
            ChatAdmin::findOrFail($id)->delete();
            return response()->json('delete',200);
        }
        return abort(404);
    }

    public function deleteAllMessage($id)
    {
        if (\Request::ajax()) {
            $messages = $this->messageByUserId($id);
            foreach ($messages as $value) {
                ChatAdmin::findOrFail($value->id)->delete();
            }

            return response()->json('delete',200);
        }
        return abort(404);
    }
    private function messageByUserId($id)
    {
        return ChatAdmin::where(function($q) use ($id){
                $q->where('from',auth()->user()->id);
                $q->where('to',$id);
                $q->where('type',0);
            })->orWhere(function($q) use ($id){
                $q->where('from',$id);
                $q->where('to',auth()->user()->id);
                $q->where('type',1);
            })->with('user')->get();
    }
}
