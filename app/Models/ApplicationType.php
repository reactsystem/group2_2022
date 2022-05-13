<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;

class ApplicationType extends Model
{
    use HasFactory;

    public function application(){
        return $this->hasMany(Application::class);
    }

    public function workType(){
        return $this->belongsTo(WorkType::class);
    }
}
