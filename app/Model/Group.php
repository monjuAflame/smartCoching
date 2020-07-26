<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    protected $guarded = [];
    protected $primaryKey = 'group_id';
    public $timestamps = true;
}
