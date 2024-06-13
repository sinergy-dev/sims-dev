<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PRCompare;
use App\PR;
use DB;
use Auth;

class PRDraft extends Model
{
    protected $table = 'tb_pr_draft';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'type_of_letter',
        'category',
        'to',
        'email',
        'phone',
        'fax',
        'attention',
        'title',
        'address',
        'issuance',
        'request_method',
        'pid',
        'lead_id',
        'term_payment',
        'quote_number',
        'nominal',
        'status',
        'status_tax',
        'isCommit',
        'created_at'
    ];

    protected $appends = ['no_pr', 'title', 'status', 'type_of_letter', 'date', 'issuance', 'status_tax', 'nominal', 'circularby', 'to', 'attention_notes', 'name','status_pr'];
    // protected $appends = ['no_pr','title', 'status', 'type_of_letter', 'date', 'issuance', 'status_tax', 'nominal', 'circularby', 'to', 'attention_notes','name','status_pr'];

    public function getToAttribute()
    {
        $data = DB::table('tb_pr')->join('tb_pr_draft', 'tb_pr.id_draft_pr', '=', 'tb_pr_draft.id', 'left')->select('tb_pr.to')->where('tb_pr_draft.id', $this->id)->first();

        return empty($data->to)?(empty(DB::table('tb_pr_draft')->where('id',$this->id)->first()->to) ? "-" : DB::table('tb_pr_draft')->where('id',$this->id)->first()->to):$data->to;
    }

    // public function getComparisonAttribute()
    // {
    //     $data = PRCompare::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_compare.id_draft_pr')
    //          // ->join('tb_pr', 'tb_pr.id_draft_pr', '=', 'tb_pr_draft.id', 'left')
    //          ->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')
    //          ->select('tb_pr_compare.to', 'tb_pr_compare.email', 'tb_pr_compare.phone', DB::raw("(CASE WHEN (tb_pr_compare.fax is null) THEN '-' ELSE tb_pr_compare.fax END) as fax"), 'tb_pr_compare.attention', 'tb_pr_compare.title', 'tb_pr_compare.address', 'tb_pr_compare.term_payment', 'tb_pr_compare.nominal', 'tb_pr_compare.note_pembanding', 'tb_pr_compare.id', 'tb_pr_compare.id_draft_pr', 'tb_pr_draft.type_of_letter', 'tb_pr_draft.request_method', 'tb_pr_draft.pid', 'tb_pr_draft.lead_id', 'name as issuance', 'tb_pr_compare.status', 'tb_pr_compare.status_tax', 'users.name', DB::raw("(CASE WHEN (tb_pr_draft.quote_number = 'null') THEN '-' ELSE quote_number END) as quote_number"))
    //          ->where('tb_pr_compare.id_draft_pr', $this->id)
    //          ->orderByRaw('FIELD(tb_pr_draft.status, "SAVED", "DRAFT", "REJECT", "VERIFIED", "COMPARING", "CIRCULAR", "DISAPPROVE", "FINALIZED", "SENDED", "REJECTED")')
    //          ->get();

    //     return $data->map(function ($item, $key){
    //      $item->product = $item->product_detail;
    //      $item->document = $item->document_detail;
    //      return $item;
    //     });
    // }

    public function getNoPrAttribute()
    {
        $data = DB::table('tb_pr')->join('tb_pr_draft', 'tb_pr.id_draft_pr', '=', 'tb_pr_draft.id', 'left')->select('no_pr')->where('tb_pr_draft.id', $this->id)->first();

        return empty($data->no_pr)?$this->id:$data->no_pr;
    }

    public function getTitleAttribute()
    {
        $data = DB::table('tb_pr')->join('tb_pr_draft', 'tb_pr.id_draft_pr', '=', 'tb_pr_draft.id', 'left')->select('tb_pr.title as title_pr')->where('tb_pr_draft.id', $this->id)->first();

        return empty($data->title_pr)?(empty(DB::table('tb_pr_draft')->where('id',$this->id)->first()->title) ? "-" : DB::table('tb_pr_draft')->where('id',$this->id)->first()->title):$data->title_pr;
    }

    public function getNominalAttribute()
    {
        $data = DB::table('tb_pr')->join('tb_pr_draft', 'tb_pr.id_draft_pr', '=', 'tb_pr_draft.id', 'left')->select('tb_pr.amount as nominal')->where('tb_pr_draft.id', $this->id)->first();

        return empty($data->nominal)?(empty(DB::table('tb_pr_draft')->where('id',$this->id)->first()->nominal) ? "0" : DB::table('tb_pr_draft')->where('id',$this->id)->first()->nominal):$data->nominal;
    }

    public function getStatusAttribute()
    {
        $data = DB::table('tb_pr_draft')->select('tb_pr_draft.status')->where('tb_pr_draft.id', $this->id)->first();
        return empty($data->status)?(empty(DB::table('tb_pr_draft')->where('id',$this->id)->first()->status) ? "-" : DB::table('tb_pr_draft')->where('id',$this->id)->first()->status):$data->status;
        // return $data->status;
    }

    public function getStatusPrAttribute()
    {
        $data = DB::table('tb_pr')->join('tb_pr_draft','tb_pr_draft.id','tb_pr.id_draft_pr')->select('tb_pr.status')->where('tb_pr_draft.id', $this->id)->first();
        return empty($data->status)?(empty(DB::table('tb_pr_draft')->where('id',$this->id)->first()->status) ? "-" : DB::table('tb_pr_draft')->where('id',$this->id)->first()->status):$data->status;
        // return $data->status;
    }

    public function getTypeOfLetterAttribute()
    {
        $data = DB::table('tb_pr_draft')->select('type_of_letter')->where('tb_pr_draft.id', $this->id)->first();
        return empty($data->type_of_letter)?(empty(DB::table('tb_pr_draft')->where('id',$this->id)->first()->type_of_letter) ? "-" : DB::table('tb_pr_draft')->where('id',$this->id)->first()->type_of_letter):$data->type_of_letter;
    }

    public function getIssuanceAttribute()
    {
        $data = DB::table('tb_pr')->join('tb_pr_draft', 'tb_pr.id_draft_pr', '=', 'tb_pr_draft.id', 'left')->select('tb_pr.issuance')->where('tb_pr_draft.id', $this->id)->first();
        return empty($data->issuance)?(empty(DB::table('tb_pr_draft')->where('id',$this->id)->first()->issuance) ? "-" : DB::table('tb_pr_draft')->where('id',$this->id)->first()->issuance):$data->issuance;
    }

    public function getNameAttribute()
    {
        $data = DB::table('tb_pr_draft')->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')->select('users.name')->where('tb_pr_draft.id', $this->id)->first();
        // return $data->name;
        return empty($data->name)?(empty($data->name) ? "-" : $data->name):$data->name;
    }

    public function getDateAttribute()
    {
        $data = DB::table('tb_pr_draft')->select('created_at')->where('id', $this->id)->first();
        return empty($data->created_at)?(empty(DB::table('tb_pr')->where('id_draft_pr',$this->id)->first()->date) ? "-" : DB::table('tb_pr_draft')->where('id',$this->id)->first()->created_at):$data->created_at;
    }

    public function getStatusTaxAttribute()
    {
        $data = DB::table('tb_pr_draft')->select('status_tax')->where('id', $this->id)->first();

        return empty($data->status_tax)?(empty(DB::table('tb_pr_draft')->where('id',$this->id)->first()->status_tax) ? "-" : DB::table('tb_pr_draft')->where('id',$this->id)->first()->status_tax):$data->status_tax;
    }

    public function getCircularByAttribute()
    {
        $data = DB::table('tb_pr_draft')->where('id',$this->id)->first();

        $territory = DB::table('users')
            ->select('id_territory')
            ->where('nik', $data->issuance)
            ->first()
            ->id_territory;

        $cek_group = DB::table('tb_pr_draft')->join('role_user', 'role_user.user_id', '=', 'tb_pr_draft.issuance')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('roles.name', 'roles.group')->where('tb_pr_draft.id', $this->id)
            ->first();

        $unapproved = DB::table('tb_pr_activity')
            ->where('tb_pr_activity.id_draft_pr', $this->id)
            ->where('tb_pr_activity.status', "UNAPPROVED")
            ->orderBy('tb_pr_activity.id',"DESC")
            ->get();

        $tb_pr_activity = DB::table('tb_pr_activity')
            ->where('tb_pr_activity.id_draft_pr', $this->id);

        if(count($unapproved) != 0){
            $tb_pr_activity->where('tb_pr_activity.id','>',$unapproved->first()->id);
        }
            
        $tb_pr_activity->where(function($query){
            $query->where('tb_pr_activity.status', 'CIRCULAR')
                ->orWhere('tb_pr_activity.status', 'FINALIZED');
        });

        $sign = DB::table('users')->join('role_user', 'role_user.user_id', '=', 'users.nik')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select(
                    'users.name', 
                    'roles.name as position', 
                    DB::raw('IF(ISNULL(`temp_tb_pr_activity`.`date_time`),"false","true") AS `signed`')
                )
                ->leftJoinSub($tb_pr_activity,'temp_tb_pr_activity',function($join){
                    // $join->on("temp_tb_pr_activity.operator","=","users.name");
                    $join->on("users.name","LIKE",DB::raw("CONCAT('%', temp_tb_pr_activity.operator, '%')"));
                })
                ->where('id_company', '1')
                ->where('status_karyawan', '!=', 'dummy');


        if ($data->type_of_letter == 'EPR') {
            if ($data->category == 'Bank Garansi') {
                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                        ->orderByRaw('FIELD(position, "BCD Manager", "Sales Manager", "Operations Director")');
                    } else {
                        $sign->whereRaw("(`roles`.`name` = 'VP Supply Chain, CPS & Asset Management' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `roles`.`name` = 'Operations Director')")
                            ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "Sales Manager", "Operations Director")');
                    }
                }

                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "Sales Manager", "Operations Director")');
            } else {
                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER')")
                        ->orderByRaw('FIELD(position, "BCD Manager", "VP Project Management", "VP Product Management & Development Solution", "Operations Director")');
                    } else {         
                       $sign->whereRaw("(`roles`.`name` = 'VP Supply Chain, CPS & Asset Management' OR `roles`.`name` = 'Operations Director' OR `roles`.`name` = 'VP Product Management & Development Solution' OR `roles`.`name` = 'VP Project Management')")
                        ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "VP Project Management", "VP Product Management & Development Solution", "Operations Director")');
                    }
                }

                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "BCD Manager", "VP Project Management", "VP Product Management & Development Solution", "Operations Director")');
            }
        } else {
            if ($cek_group->group == 'Project Management') {

                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                        ->orderByRaw('FIELD(position, "BCD Manager", "VP Project Management", "Operations Director")');
                    } else {
                        $sign->whereRaw("(`roles`.`name` = 'VP Supply Chain, CPS & Asset Management' OR `roles`.`name` = 'VP Project Management' OR `roles`.`name` = 'Operations Director')")
                        ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "VP Project Management", "Operations Director")');
                    }
                }

                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "VP Project Management", "Operations Director")');

            } 
            elseif ($cek_group->group == 'Supply Chain, CPS & Asset Management') {

                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'MSM' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                        ->orderByRaw('FIELD(position, "BCD Manager", "Operations Director")');
                    } else {
                        $sign->whereRaw("(`roles`.`name` = 'VP Supply Chain, CPS & Asset Management' OR `roles`.`name` = 'Operations Director')")
                        ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "Operations Director")');
                    }
                }

                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'MSM' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "Operations Director")');

            }elseif ($cek_group->group == 'Product Management & Development') {

                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                        ->orderByRaw('FIELD(position, "BCD Manager", "VP Product Management & Development Solution", "Operations Director")');
                    } else {
                        $sign->whereRaw("(`roles`.`name` = 'VP Supply Chain, CPS & Asset Management' OR `roles`.`name` = 'VP Product Management & Development Solution' OR `roles`.`name` = 'Operations Director')")
                        ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "VP Product Management & Development Solution", "Operations Director")');
                    }
                }

                // $sign->whereRaw("( `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "BCD Manager","Operations Director")');

            } elseif ($cek_group->group == 'Solution Implementation & Managed Service') {
                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'ENGINEER MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                        ->orderByRaw('FIELD(position, "BCD Manager", "VP Solution Implementation & Managed Service","Operations Director")');
                    } else {
                        $sign->whereRaw("(`roles`.`name` = 'VP Supply Chain, CPS & Asset Management' OR `roles`.`name` = 'VP Solution Implementation & Managed Service' OR `roles`.`name` = 'Operations Director')")
                        ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "VP Solution Implementation & Managed Service","Operations Director")');
                    }
                }
                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'ENGINEER MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "VP Solution Implementation & Managed Service","Operations Director")');

            } elseif ($cek_group->group == 'Human Capital') {
                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `roles`.`name` = 'Renumeration, Personalia & GS Manager' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                        ->orderByRaw('FIELD(position, "BCD Manager", "Renumeration, Personalia & GS Manager", "Operations Director")');
                    } else {
                        $sign->whereRaw("(`roles`.`name` = 'VP Supply Chain, CPS & Asset Management' OR `roles`.`name` = 'Renumeration, Personalia & GS Manager' OR `roles`.`name` = 'Operations Director')")
                        ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "Renumeration, Personalia & GS Manager", "Operations Director")');
                    }
                }
                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `roles`.`name` = 'Renumeration, Personalia & GS Manager' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "Renumeration, Personalia & GS Manager", "Operations Director")');

            } elseif ($cek_group->group == 'sales') {
                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                        ->orderByRaw('FIELD(position, "BCD Manager", "Sales Manager", "Operations Director")');
                    } else {
                        $sign->whereRaw("(`roles`.`name` = 'VP Supply Chain, CPS & Asset Management' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `roles`.`name` = 'Operations Director')")
                        ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "Sales Manager", "Operations Director")');
                    }
                }
                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Supply Chain, CPS & Asset Management", "Sales Manager", "Operations Director")');
            }
        }

        // return $sign->get();

        return empty($sign->get()->where('signed','false')->first()->name)?'-':$sign->get()->where('signed','false')->first()->name;
    }

    public function getAttentionNotesAttribute()
    {
        $notes = DB::table('tb_pr_notes')->select('id', 'resolve')->where('id_draft_pr', $this->id)->orderByRaw('FIELD(resolve, "True", "False")')->get();

        $nik = Auth::User()->nik;
        $roles_manager = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('user_id')->where('user_id', $nik)->orWhere('name', 'like', '%MANAGER%')->first(); 

        foreach ($notes as $key => $value) {
            if (isset($roles_manager)) {
                if ($value->resolve == 'False') {
                    $notes = 'False';
                } else {
                    $notes = 'True';
                }
            }else{
                $notes = 'True';
            }
        }

        // return $notes;
        return empty($notes)?"-":$notes;
    }
}
