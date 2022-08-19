<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RequestChange;

use App\TicketingActivity;
use App\TicketingResolve;
use App\Sales;
use App\SalesChangeLog;
use App\TB_Contact;
use App\PIDRequest;
use App\SalesProject;

use Auth;
use Carbon\Carbon;

class RequestChangeController extends Controller
{
    //

    public function index(Request $req){

        $requestChange = RequestChange::find($req->id_requestChange);

        if($requestChange->type == "Re-Open Ticket"){
            $this->reOpenTicket($requestChange->object_id,$requestChange);
        } elseif ($requestChange->type == "Change Customer"){
            $this->changeCustomer($requestChange->object_id,$requestChange->parameter1_after,$requestChange);
        }elseif ($requestChange->type == "Change Nominal"){
            $this->changeNominal($requestChange->object_id,$requestChange->parameter1_after,$requestChange);
        }

        return $this->closing($requestChange);

        // return $requestChange;

    }

    public function reOpenTicket($id_ticket,$requestChange){
        // 1. Delete Close or Cancel Activity
        // 1a. If close, delete record for resolve
        // 2. Add activity for re-open ticket

        // $requestChange = RequestChange::find($id_requestChange);

        $ticket_activity = TicketingActivity::where('id_ticket','=',$id_ticket)
            ->orderBy('id','DESC');

        if($ticket_activity->first()->note == "CLOSE"){
            $ticket_resolve = TicketingResolve::where('id_ticket','=',$id_ticket)->first();
            $ticket_resolve->delete();
        }

        $ticket_activity->first()->delete();

        $activityTicketReOpen = new TicketingActivity();
        $activityTicketReOpen->id_ticket = $id_ticket;
        $activityTicketReOpen->date = date("Y-m-d H:i:s.000000");
        $activityTicketReOpen->activity = "ON PROGRESS";
        $activityTicketReOpen->operator = Auth::user()->name;
        $activityTicketReOpen->note = "Re-Open Ticket - Reason : " . $requestChange->parameter1_before;

        $activityTicketReOpen->save();

        

        return "Re-Open Ticket Success";
    }

    public function changeCustomer($lead_id,$cus,$requestChange)
    {
        $update = Sales::where('lead_id', $lead_id)->first();
        $update->id_customer = $cus;
        $update->update();

        $getCus = TB_Contact::select('customer_legal_name')->where('id_customer', $cus)->first();

        $add_changelog = new SalesChangeLog();
        $add_changelog->lead_id = $lead_id;
        $add_changelog->nik = Auth::User()->nik;
        $add_changelog->status = 'Update Lead with new Customer ' . $getCus->customer_legal_name;
        $add_changelog->save();
    }

    public function changeNominal($lead_id,$nominal,$requestChange)
    {
        $update = Sales::where('lead_id', $lead_id)->first();
        $update->deal_price = $nominal;
        $update->amount = $nominal;
        $update->update();

        $cek = SalesProject::where('lead_id', $lead_id)->count();
        if ($cek != 0) {
            $update_pid = SalesProject::where('lead_id', $lead_id)->first();
            $update_pid->amount_idr = $nominal;
            $update_pid->update();
        }

        $add_changelog = new SalesChangeLog();
        $add_changelog->lead_id = $lead_id;
        $add_changelog->nik = Auth::User()->nik;
        $add_changelog->status = 'Update Lead with Amount ';
        $add_changelog->submit_price  = $update->deal_price;
        $add_changelog->save();
    }

    public function closing($requestChange){

        $requestChange->status = "Done";
        $requestChange->change_by = Auth::user()->name;
        $requestChange->change_at = Carbon::now()->toDateTimeString();
        $requestChange->save();

        // $data = collect([
        //     'type' => $type,
        //     'requester' => $requester,
        //     'type' => $type,
        // ]);
        return view('closeRequestChange',compact('requestChange'));
    }
}
