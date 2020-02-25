<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FollowUpCuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FollowUpCuti:followupcuti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email ke ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
