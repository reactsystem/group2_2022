<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;

class ApplicationType extends Model
{
    use HasFactory;

    public function application(){
        return $this->hasOne(Application::class);
    }
}