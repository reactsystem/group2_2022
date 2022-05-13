<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkTime;
use App\Models\ApplicationType;

class WorkType extends Model
{
    use HasFactory;

    public function workTime(){
        return $this->hasMany(WorkTime::class);
    }

    public function applicationType(){
        return $this->hasMany(ApplicationType::class);
    }
}
