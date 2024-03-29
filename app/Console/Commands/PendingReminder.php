<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TicketingPendingReminder;
use App\TicketingActivity;

class PendingReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $data = TicketingPendingReminder::whereRaw("DATE_FORMAT(`ticketing__pending_reminder`.`remind_time`, '%Y-%m-%d %H:%i') = DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i')")
            ->where('remind_success','=','FALSE');
        syslog(LOG_INFO, "Test count pending remainder success " . $data->count());
        if($data->count() > 0){
            syslog(LOG_INFO, "Loop for test pending remainder start ");
            foreach ($data->get() as $key => $value) {
                syslog(LOG_INFO, "Data for test pending remainder " . $key);
                $this->updateProgressTicket($value->id_ticket);
                $this->updatePendingReminder($value->id);
            }
            syslog(LOG_INFO, "Loop for test pending remainder finish ");
        } else {
            syslog(LOG_INFO, "Not found this time ");
        }
    }

    public function updateProgressTicket($id_ticket){
        $activityTicketUpdate = new TicketingActivity();
        $activityTicketUpdate->id_ticket = $id_ticket;
        $activityTicketUpdate->date = date("Y-m-d H:i:s.000000");
        $activityTicketUpdate->activity = "ON PROGRESS";
        $activityTicketUpdate->operator = 'System';
        $activityTicketUpdate->note = 'This ticket is continued automatically by the system';

        $activityTicketUpdate->save();
    }

    public function updatePendingReminder($id_remaind){
        $remainder = TicketingPendingReminder::find($id_remaind);
        $remainder->remind_success = "TRUE";

        $remainder->save();

    }
}
