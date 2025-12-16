<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'worker_id',
        'date',
        'check_in',
        'check_out'
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
