<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentAsProgrameAndAcademicYear extends Model
{
    protected $table = 'student_as_programe_and_academic_years';
    protected $fillable = ['student_id','academic_id','level_id'];
    protected $primaryKey = 's_p_a_id';
    public $timestamps = false;
}
