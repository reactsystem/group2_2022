<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApplicationType;
use App\Models\User;

class Application extends Model
{
    use HasFactory;

    public function applicationType(){
        return $this->hasOne(ApplicationType::class);
    }

    public function user(){
        return $this->hasOne(User::class);
    }
}
