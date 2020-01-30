<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\Mail\ClosedateMail;

use App\User;
use DB;

use Notification;

class ClosingDateEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'closedate:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'H-7 Closing date project';

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
        // $this->info('The emails are send successfully!');

        $todayDate = date("Y-m-d");

        $closedate = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->select('lead_id', 'email')
                        ->where(DB::raw("DATEDIFF(now(), closing_date)"), '=', '-7')
                        ->where('id_company', '1')
                        ->get();

        $users = User::select('email', 'name', 'nik')
                        ->where('name', 'Arkhab Maulana Uzlahbillah')
                        ->orWhere('name', 'Ladinar Nanda Aprilia')
                        ->get();

        $usersales = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->select('email', 'name', 'sales_lead_register.nik')
                        ->where(DB::raw("DATEDIFF(now(), closing_date)"), '=', '-7')
                        ->where('id_company', '1')
                        ->groupBy('email')
                        ->get();

        $presales = DB::table('sales_solution_design')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
                        ->select('email', 'name', 'sales_solution_design.nik')
                        ->where(DB::raw("DATEDIFF(now(), closing_date)"), '=', '-7')
                        ->where('id_company', '1')
                        ->groupBy('email')
                        ->get();

        // Email mas ganjar Presales Manager
        $presalesm = DB::table('users')
                        ->select('email', 'nik')
                        ->where('id_division', 'TECHNICAL PRESALES')
                        ->where('id_position', 'MANAGER')
                        ->where('id_company', '1')
                        ->first();

        if(count($closedate) != 0) {

            // Email ke sales
            foreach($usersales as $data) {
                Mail::to($data->email)->send(new ClosedateMail($data->nik));
            }

            // Email ke presales manager dan assigned
            foreach($presales as $datas) {
                if($datas->email == $presalesm->email) {
                    Mail::to($presalesm->email)->send(new ClosedateMail($presalesm->nik));
                } else {
                    Mail::to($datas->email)->send(new ClosedateMail($datas->nik));
                    Mail::to($presalesm->email)->send(new ClosedateMail($presalesm->nik));
                }
            }

            // foreach($presales as $datas) {
            //     Mail::to($datas->email)->send(new ClosedateMail($datas->nik));
            // }

            // Coba
            // foreach($usersales as $data) {
            //     Mail::to('arkhab@sinergy.co.id')->send(new ClosedateMail($data->nik));
            // }

        }

        // Notification::send($users, new ClosedateMail());
    }
}
