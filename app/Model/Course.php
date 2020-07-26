<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $fillable = ['academic_id','level_id','shift_id','time_id','group_id','batch_id','start_date','end_date','description','active'];
    protected $primaryKey = 'course_id';
    public $timestamps = false;
}
