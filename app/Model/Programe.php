<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Programe extends Model
{
    protected $table = 'programes';
    protected $guarded = [];
    protected $primaryKey = 'programe_id';
    public $timestamps = true;
}
