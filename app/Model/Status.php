<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses';
    protected $fillable = ['student_id','course_id'];
    protected $primaryKey = 'status_id';
    public $timestamps = false;
    
}
