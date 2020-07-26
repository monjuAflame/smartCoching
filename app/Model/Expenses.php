<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $table = 'expenses';
    protected $fillable = ['date','total_expense'];
    protected $primaryKey = 'expense_id';
    public $timestamps = false;
}
