<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DailyExpense extends Model
{
    protected $table = 'daily_expense';
    protected $fillable = ['date','user_id','e_type_id','expense_id','description','amount'];
    protected $primaryKey = 'd_e_id';
    public $timestamps = false;
}
