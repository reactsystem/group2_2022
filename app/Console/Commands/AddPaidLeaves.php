<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use App\Models\AddPaidLeave;
use App\Models\PaidLeave;

class AddPaidLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:addPaidLeave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '有給付与日が来たら有給を付与する&二年経過した分はソフトデリートします。';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
    }
}
