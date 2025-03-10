<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\User;
use Auth;

class Claim extends Model
{
    protected $table = 'tb_claim';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'no_monreq',
        'issuane',
        'nominal',
        'status',
        'date',
        'parent_id_drive',
        'date_add'
    ];

    public $timestamps = false;

    protected $appends = ['notes_claim','circularBy'];

    public function getNotesclaimAttribute()
    {
        $data = DB::table('tb_claim_notes')
            ->join('tb_claim','tb_claim.id','=','tb_claim_notes.id_claim')
            ->select(
                'sub_category',
                'notes'
            )
            ->where('tb_claim_notes.status','NEW')
            ->where('tb_claim_notes.id_claim', $this->id)
            ->get();

        return $data;
    }

    public function getCircularByAttribute()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;

        $data = DB::table('tb_claim')->where('id',$this->id)->first();

        $cek_group = DB::table('tb_claim')
            ->join('role_user', 'role_user.user_id', '=', 'tb_claim.issuance')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('roles.name', 'roles.group', 'mini_group')
            ->where('tb_claim.id', $this->id)
            ->first();

        if ($cek_group) {
            $name = $cek_group->name;
            $group = $cek_group->group;
            $mini_group = $cek_group->mini_group;
        } else {
            $name = null;
            $group = null;
            $mini_group = null;
        }

        $unapproved = DB::table('tb_claim_activity')
            ->where('tb_claim_activity.id_claim', $this->id)
            ->where('tb_claim_activity.status', "UNAPPROVED")
            ->orderBy('tb_claim_activity.id',"DESC")
            ->get();

        $tb_claim_activity = DB::table('tb_claim_activity')
            ->where('tb_claim_activity.id_claim', $this->id);

        if(count($unapproved) != 0){
            $tb_claim_activity->where('tb_claim_activity.id','>',$unapproved->first()->id);
        }
            
        $tb_claim_activity->where(function($query){
            $query->where('tb_claim_activity.status', 'NEW')
                ->orWhere('tb_claim_activity.status', 'CIRCULAR')->orWhere('tb_claim_activity.status', 'APPROVED');
        });

        $sign = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select(
                    'users.name', 
                    'roles.name as position', 
                    'ttd',
                    'email',
                    'avatar',
                    DB::raw("IFNULL(SUBSTR(`temp_tb_claim_activity`.`date_time`,1,10),'-') AS `date_sign`"),
                    DB::raw('IF(ISNULL(`temp_tb_claim_activity`.`date_time`),"false","true") AS `signed`')
                )
            ->leftJoinSub($tb_claim_activity,'temp_tb_claim_activity',function($join){
                $join->on("users.name","LIKE",DB::raw("CONCAT('%', temp_tb_claim_activity.operator, '%')"));
            })
            ->where('id_company', '1')
            ->where('status_karyawan','!=','dummy');

        if(Str::contains($name, 'VP')){
            foreach ($sign->get() as $key => $value) {
                $sign->whereRaw("(`roles`.`name` = 'Chief Operating Officer')")
                    ->orderByRaw('FIELD(position, "Chief Operating Officer")');
            }
        } elseif(Str::contains($name, 'Manager') && $name != 'Project Manager'){
            if($name == 'People Operations & Services Manager' &&  $name == 'Organizational & People Development Manager'){
                $sign->whereRaw("(`roles`.`name` = 'VP Program & Project Management' OR `roles`.`name` = 'Chief Operating Officer')")->orderByRaw("CASE 
                        WHEN position LIKE 'VP%' THEN 1
                        WHEN position = 'Chief Operating Officer' THEN 2
                        ELSE 3 END ");
            } elseif ($name == 'VP Sales' || $name == 'VP Financial & Accounting'){
                $sign->where('roles.name','Chief Executive Officer')->orderByRaw('FIELD(position, "Chief Executive Officer")');
            } else {
                $sign->whereRaw(
                    "(`roles`.`name` LIKE ? AND `roles`.`group` = ? OR `roles`.`name` = ? )", 
                    ['VP%', $group, 'Chief Operating Officer']
                )->orderByRaw("CASE 
                        WHEN position LIKE 'VP%' THEN 1
                        WHEN position = 'Chief Operating Officer' THEN 2
                        ELSE 3 END");

                // return $sign->get();
            }
        } elseif($name == 'Chief Operating Officer'){
            $sign->where('roles.name','Chief Executive Officer')->orderByRaw('FIELD(position, "Chief Executive Officer")');
        } else {
            if ($mini_group == 'Human Capital') {
                $sign->whereRaw(
                    "(`roles`.`mini_group` = ? AND `roles`.`name` LIKE ? AND `roles`.`name` != ? OR `roles`.`name` = ? OR `roles`.`name` = ?)", 
                    [$mini_group, '%Manager', 'Project Manager', 'VP Program & Project Management', 'Chief Operating Officer']
                )->orderByRaw("CASE 
                    WHEN position LIKE '%Manager' THEN 1
                    WHEN position LIKE 'VP%' THEN 2
                    WHEN position = 'Chief Operating Officer' THEN 3
                    ELSE 4 END ");
            } elseif ($name == 'Account Executive') {
                $sign->whereRaw(
                    "(`users`.`id_territory` = ? AND `users`.`id_division` = ? AND `users`.`id_position` = ? AND `users`.`status_karyawan` != ?)", 
                    [$ter, $div, 'MANAGER', 'dummy']
                );
            } else if ($mini_group == 'Product Development Specialist' || $mini_group == 'Supply Chain & IT Support' || $mini_group == 'Internal Operation Support') {
                $sign->whereRaw("(`roles`.`name` LIKE 'VP%' OR `roles`.`name` = 'Chief Operating Officer')")
                    ->where('group',$group)->orderByRaw("CASE 
                        WHEN position LIKE 'VP%' THEN 1
                        WHEN position = 'Chief Operating Officer' THEN 2
                        ELSE 3 END ");
            } else if ($group == 'Program & Project Management') {
                $sign->whereRaw(
                        "(`roles`.`group` = ? AND `roles`.`name` LIKE ?  AND `roles`.`name` != ? OR `roles`.`name` like ? OR `roles`.`name` = ?)", 
                        [$group, '%Manager', 'Project Manager', '%VP Program & Project Management%', 'Chief Operating Officer']
                    )
                    ->orderByRaw("CASE 
                        WHEN position LIKE '%Project Management Manager%' THEN 1
                        WHEN position LIKE '%VP Program & Project Management%' THEN 2
                        WHEN position = 'Chief Operating Officer' THEN 3
                        ELSE 4 END ");
            } else {
                $sign->whereRaw(
                        "(`roles`.`mini_group` = ? AND `roles`.`name` LIKE ?  AND `roles`.`name` != ? OR `roles`.`name` LIKE ? OR `roles`.`name` = ?)", 
                        [$mini_group, '%Manager', 'Project Manager', 'VP%', 'Chief Operating Officer']
                    )
                    ->orderByRaw("CASE 
                        WHEN position LIKE '%Manager' THEN 1
                        WHEN position LIKE 'VP%' THEN 2
                        WHEN position = 'Chief Operating Officer' THEN 3
                        ELSE 4 END ");
            }
        }

        return empty($sign->get()->where('signed','false')->first()->name)?'-':$sign->get()->where('signed','false')->first()->name;
    }
}
