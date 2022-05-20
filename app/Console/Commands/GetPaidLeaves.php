<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetPaidLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getPaidLeave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '有給休暇を取得して実行します。';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}
