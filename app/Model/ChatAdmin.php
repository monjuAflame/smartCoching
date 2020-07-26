<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ChatAdmin extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function user(){
    	return $this->belongsTo(User::class,'from');
    }
}
