<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Sales;
use GuzzleHttp\Client;
use DB;
use Carbon\carbon;


class UpdateYearByClosingDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateYearByClosingDate:UpdateYearByClosingDate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update year lead id yang win, dan closing datenya tidak sama dengan tahun pembuatan';

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

        // echo("cuti". $total_cuti);
        syslog(LOG_ERR, "Reset Cuti Start");
        syslog(LOG_ERR, "-------------------------");
        
        $dataLead = DB::table('sales_lead_register')->select('year','closing_date','lead_id')
            ->whereYear('closing_date',date('Y'))
            ->where('result','WIN')
            ->get();

        // echo($dataLead);

        // return $update;

        // $update = User::where('nik',$reset->nik)->first();
        //     // $data->cuti2 = 12 - $total_cuti;
        // $update->cuti2 = $total_cuti;
        // $update->update();

        foreach ($dataLead as $data) {
            // syslog(LOG_ERR, "Reset Cuti for " . $data->lead_id);
            echo($data->lead_id);
            // syslog(LOG_ERR, "before reset cuti : " . $data->cuti);
            // syslog(LOG_ERR, "before reset cuti2 : " . $data->cuti2);
            // syslog(LOG_ERR, "-------------------------");
            $data = Sales::where('lead_id',$data->lead_id)->first(); 
            $data->year  = date('Y');
            
            $data->save();

        }
    }
}
