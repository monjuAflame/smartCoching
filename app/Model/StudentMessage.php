<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentMessage extends Model
{
    protected $table = 'student_messages';
    protected $fillable = ['user_id','message','mobiles','send_date'];
    protected $primaryKey = 'message_id';
    public $timestamps = false;
}
