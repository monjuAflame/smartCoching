<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = [
    						'student_id',
    						'course_id',
    						'user_id',
    						'name',
    						'nick_name',
    						'dob',
    						'sex',
    						'status',
    						'nationality',
    						'zipcode',
                            'phone',
    						'active',
    						'village',
    						'post_office',
    						'updistrict',
    						'district',
    						'current_address',
    						'dateregistered',
    						];

    protected $primaryKey = 'id';
    public $timestamps = false;
   
}
