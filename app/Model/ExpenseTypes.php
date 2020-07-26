<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ExpenseTypes extends Model
{
    protected $table = 'expense_types';
    protected $fillable = ['expense_type'];
    protected $primaryKey = 'e_type_id';
    public $timestamps = false;
}
