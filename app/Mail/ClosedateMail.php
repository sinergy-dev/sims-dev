<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use DB;

class ClosedateMail extends Mailable
{
    use Queueable, SerializesModels;
    public $closedates;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($closedates)
    {
        $this->closedates = $closedates;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $todayDate = date("Y-m-d");

        // $usersales = DB::table('sales_lead_register')
        //                 ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
        //                 ->select('email')
        //                 ->where(DB::raw("DATEDIFF(now(), closing_date)"), '=', '-21')
        //                 ->groupBy('email')
        //                 ->get();

        $userpresales = DB::table('users')
                            ->select('id_division', 'id_position', 'id_territory')
                            ->where('nik', $this->closedates)
                            ->first();

        if ($userpresales->id_division == 'TECHNICAL PRESALES') {
            if($userpresales->id_position == 'MANAGER') {
                // Presales manager
                $closedate = DB::table('sales_solution_design')
                ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                ->join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
                ->select('sales_lead_register.lead_id', 'sales_lead_register.opp_name', 'sales_solution_design.nik')
                ->where(DB::raw("DATEDIFF(now(), closing_date)"), '=', '-7')
                ->where('id_company', '1')
                ->get();
            } else {
                // Presales assigned
                $closedate = DB::table('sales_solution_design')
                            ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                            ->join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
                            ->select('sales_lead_register.lead_id', 'sales_lead_register.opp_name', 'sales_solution_design.nik')
                            ->where(DB::raw("DATEDIFF(now(), closing_date)"), '=', '-7')
                            ->where('sales_solution_design.nik', $this->closedates)
                            ->get();
            }
        }else{
            $closedate = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->select('lead_id', 'opp_name')
                        ->where(DB::raw("DATEDIFF(now(), closing_date)"), '=', '-7')
                        ->where('sales_lead_register.nik', $this->closedates)
                        ->get();
        }

        $presales = DB::table('sales_solution_design')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
                        ->select('email', 'name', 'sales_solution_design.nik')
                        ->where(DB::raw("DATEDIFF(now(), closing_date)"), '=', '-7')
                        ->where('id_company', '1')
                        ->groupBy('email')
                        ->get();

        return $this->view('sales.closedate', compact('todayDate', 'closedate', 'usersales', 'presales'));
    }
}
