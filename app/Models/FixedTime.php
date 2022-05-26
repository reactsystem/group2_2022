<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedTime extends Model
{
    use HasFactory;

    public function getRoundTime() {
        $fixed_time = FixedTime::first();

        // 退勤時刻を丸める範囲を取得する
        if (strtotime('01:00:00') > strtotime($fixed_time->rest_time)) {
            $round_time = strtotime('01:00:00') - strtotime($fixed_time->rest_time);
            $round_time = gmdate('H:i:s', $round_time);

            $from = strtotime('00:00:00');
            $end = strtotime($round_time);
            $minutes = ($end - $from) / 60;
            $round_time = "+" . $minutes . "min";
        } else {
            $round_time = '+0 min';
        }
        return $round_time;
    }
}
