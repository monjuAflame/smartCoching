<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    protected $table = 'times';
    protected $guarded = [];
    protected $primaryKey = 'time_id';
    public $timestamps = true;
}
