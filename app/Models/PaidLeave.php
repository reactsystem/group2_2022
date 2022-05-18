<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PaidLeave extends Model
{
    use HasFactory;

    const UPDATED_AT = NULL;

    public function user(){
        return $this->hasOne(User::class);
    }
}
