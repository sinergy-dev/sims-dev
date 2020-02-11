<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;


class ResetAwalTahun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ResetAwalTahun:resetawaltahun';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'mendefine nilai cuti2 setiap 1 januari';

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
        $reset = User::select('nik','name')->get();

        foreach ($reset as $data) {
            // print_r($data->name . $data->nik . "\n");
            
            $update = User::where('nik',$data->nik)->first();
            $data->cuti2 = 12 - 4;
            $data->update();

        }
    }
}
