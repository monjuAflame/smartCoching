<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\AdminChat;
use Carbon\Carbon;
use App\User;


class AdminChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adminChat = AdminChat::join('users','users.id','=','admin_chat.user_id')
                        ->select(
                            'admin_chat.user_id',
                            'users.name',
                            'users.photo',
                            'users.created_at',
                            'admin_chat.chat_id',
                            'admin_chat.chated_date',
                            'admin_chat.message')
                        ->orderBy('admin_chat.chat_id','desc')
                        ->paginate(5);
        $admin = User::select('name','photo','created_at','id')->get();
        return compact('adminChat','admin');
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
            'message' => 'required|string|max:100'            
        ]);

        $adminChat = new AdminChat;
        $adminChat->user_id = $request->user_id;
        $adminChat->chated_date = Carbon::now();
        $adminChat->message = $request->message;
        $adminChat->save();

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
