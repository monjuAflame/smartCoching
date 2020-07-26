<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'shifts';
    protected $guarded = [];
    protected $primaryKey = 'shift_id';
    public $timestamps = true;
}
