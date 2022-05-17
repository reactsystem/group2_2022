<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NullCheck implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value = NULL){
            return true;
        } else {
        return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '「欠勤」の場合は時刻を入力できません';
    }
}
