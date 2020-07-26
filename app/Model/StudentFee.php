<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    protected $table = 'student_fees';
    protected $fillable = ['fee_id','student_id','level_id','amount','discount','total_paid'];
    protected $primaryKey = 's_fee_id';
    public $timestamps = false;
}
