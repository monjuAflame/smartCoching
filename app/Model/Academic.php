<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Academic extends Model
{
    protected $table = 'academics';
    protected $fillable = ['academic'];
    protected $primaryKey = 'academic_id';
}
