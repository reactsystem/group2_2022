<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class AddPaidLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-paid-leaves';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::where('joining', '=', '');
    }
}
