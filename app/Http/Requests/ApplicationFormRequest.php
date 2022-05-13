<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\FixedTime;
use Carbon\Carbon;

class ApplicationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // 終業時間の14分後を取得
        $left_time = new Carbon(FixedTime::first()->left_time);
        $left_time->addMinutes(14);
        $left_time = $left_time->toTimeString('minute');

        return [
            'date' => 'required|date|date_format:Y/m/d',
            'start_time' => ['date_format:H:i','after:'.$left_time],
            'end_time' => 'date_format:H:i|after:start_time',
            'reason' => 'nullable|string|max:60'
        ];
    }

    public function attributes()
    {
        return[
            'date' => '申請日',
            'start_time' => '開始時間',
            'end_time' => '終了時間',
            'reason' => '申請理由',
        ];
    }

    public function messages()
    {
        // 終業時間の14分後を取得
        $left_time = new Carbon(FixedTime::first()->left_time);
        $left_time->addMinutes(15);
        $left_time = $left_time->toTimeString('minute');

        return[
            'date.date_format' => '申請日は ○○○○/○○/○○ という形で指定してください。',
            'start_time.date_format' => '開始時間は ○○:○○ という形で指定してください',
            'start_time.after' => '開始時間は'.$left_time.'以降の時間を指定してください。',
            'end_time.date_format' => '終了時間は ○○:○○ という形で指定してください',
            'end_time.after' => '終了時間は'.$this->start_time.'以降の時間を指定してください。',
        ];
    }
}
