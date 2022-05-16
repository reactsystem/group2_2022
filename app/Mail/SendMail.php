<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('application.mail')
            ->subject('【勤怠管理システム】申請結果通知')
            ->from('sample@sample.com', '勤怠管理システム')
            ->subject('【勤怠管理システム】申請結果通知')
            ->with('data', $this->data);
    }
}
