<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Feetype extends Model
{
    protected $table = 'feetypes';
    protected $fillable = ['fee_type'];
    protected $primaryKey = 'fee_type_id';
    public $timestamps = false;
}
