<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminChat extends Model
{
    protected $table = 'admin_chat';
    protected $fillable = ['user_id','deliver_date','message'];
    protected $primaryKey = 'chat_id';
    public $timestamps = false;

}
