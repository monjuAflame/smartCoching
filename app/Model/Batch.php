<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'batches';
    protected $guarded = [];
    protected $primaryKey = 'batch_id';
    public $timestamps = true;
}
