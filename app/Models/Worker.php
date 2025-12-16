<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $fillable = [
        'name',
    ];
    
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
