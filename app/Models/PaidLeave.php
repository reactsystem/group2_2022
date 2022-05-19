<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaidLeave extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    const UPDATED_AT = NULL;
    const CREATED_AT = NULL;

    public function user(){
        return $this->hasOne(User::class);
    }
}
