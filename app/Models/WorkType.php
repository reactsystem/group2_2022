<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkTime;

class WorkType extends Model
{
    use HasFactory;

    public function workTime(){
        return $this->hasOne(WorkTime::class);
    }
}
