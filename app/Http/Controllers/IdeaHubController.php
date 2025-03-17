<?php


namespace App\Http\Controllers;


use App\IdeaHub;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class IdeaHubController extends Controller
{
    public function index()
    {
        $sidebar_collapse = true;
        $role = Auth::user()->roles()->first()->name;
        $divisi = DB::table('roles')->select('group')
            ->where('group','Sales')
            ->orwhereIn('acronym', ['SPM','PPM','SSM','ICM','HCM'])
            ->groupBy('group')
            ->get()->pluck('group')->toArray();

        return view('idea_hub/index',compact('sidebar_collapse', 'role', 'divisi'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function getCount()
    {
        $role = Auth::user()->roles()->first()->name;

        if ($role == 'Chief Operating Officer' || $role == 'Chief Executive Officer' || $role == 'VP Solutions & Partnership Management'){
            $count_spm = IdeaHub::where('divisi', 'Solutions & Partnership Management')->count();
            $count_ppm = IdeaHub::where('divisi', 'Program & Project Management')->count();
            $count_ssm = IdeaHub::where('divisi', 'Synergy System Management')->count();
            $count_icm = IdeaHub::where('divisi', 'Internal Chain Management')->count();
            $count_hcm = IdeaHub::where('divisi', 'Human Capital Management')->count();
            $count_sal = IdeaHub::where('divisi', 'Sales')->count();
        }

        return collect([
            'count_spm' => $count_spm,
            'count_ppm' => $count_ppm,
            'count_ssm' => $count_ssm,
            'count_icm' => $count_icm,
            'count_hcm' => $count_hcm,
            'count_sal' => $count_sal
        ]);
    }

    public function getDataByFilter(Request $request)
    {
        $territory = Auth::user()->id_territory;
        $role = Auth::user()->roles()->first()->name;
        $query = DB::table('tb_idea_hub as i')->join('users as u', 'i.nik', 'u.nik')
            ->select( 'i.id',
                'i.nik',
                'u.name as name',
                'i.divisi as divisi',
                'i.ide',
                'i.konsep_bisnis',
                'i.created_at',
                DB::raw('DATE(i.created_at) as date'));
        if ($role == 'VP Program & Project Management'){
            $query = $query->where('i.divisi', 'Program & Project Management');
        }elseif ($role == 'VP Synergy System Management'){
            $query = $query->where('i.divisi', 'Synergy System Management');
        }elseif ($role == 'VP Internal Chain Management'){
            $query = $query->where('i.divisi', 'Internal Chain Management');
        }elseif ($role == 'VP Human Capital Management'){
            $query = $query->where('i.divisi', 'Human Capital Management');
        }elseif ($role == 'VP Financial & Accounting'){
            $query = $query->where('i.divisi', 'Financial And Accounting');
        }elseif ($role == 'VP Sales'){
            $query = $query->where('i.divisi', 'Sales')->where('u.id_territory', $territory);
        }elseif ($role == 'VP Solutions & Partnership Management' || $role == 'Chief Operating Officer' || $role == 'Chief Executive Officer'){
            $query = $query->where('i.divisi','!=', null);
        }else{
            $query = $query->where('i.nik', Auth::user()->nik);
        }

        if($request->startDate != '' && $request->endDate != '' ){
            $query = $query->whereDate('i.created_at', '>=', $request->startDate)
                ->whereDate('i.created_at', '<=', $request->endDate);
        }

        $query = $query
            ->orderBy('i.created_at', 'desc');


        $result = $query->get();

        return array('data' => $result);
    }

    public function getDataPoint(Request $request)
    {
        $territory = Auth::user()->id_territory;
        $role = Auth::user()->roles()->first()->name;
        $query = DB::table('tb_idea_hub_point as i')
            ->join('users as u', 'i.nik', 'u.nik')
            ->join('role_user as ru', 'i.nik', 'ru.user_id')
            ->join('roles as r', 'ru.role_id', 'r.id')
            ->select( 'i.id',
                'i.nik',
                'u.name as name',
                'point',
                'i.created_at');
        if ($role == 'VP Program & Project Management'){
            $query = $query->where('r.group', 'Program & Project Management');
        }elseif ($role == 'VP Synergy System Management'){
            $query = $query->where('r.group', 'Synergy System Management');
        }elseif ($role == 'VP Internal Chain Management'){
            $query = $query->where('r.group', 'Internal Chain Management');
        }elseif ($role == 'VP Human Capital Management'){
            $query = $query->where('r.group', 'Human Capital Management');
        }elseif ($role == 'VP Financial & Accounting'){
            $query = $query->where('r.group', 'Financial And Accounting');
        }elseif ($role == 'VP Sales'){
            $query = $query->where('r.group', 'Sales')->where('u.id_territory', $territory);
        }elseif ($role == 'VP Solutions & Partnership Management' || $role == 'Chief Operating Officer' || $role == 'Chief Executive Officer'){
            $query = $query->where('r.group','!=', null);
        }else{
            $query = $query->where('i.nik', Auth::user()->nik);
        }

        if($request->startDate != '' && $request->endDate != '' ){
            $query = $query->whereDate('i.created_at', '>=', $request->startDate)
                ->whereDate('i.created_at', '<=', $request->endDate);
        }

        $query = $query
//            ->orderBy('u.name', 'asc')
            ->orderBy('point', 'desc');

        $result = $query->get();

        return array('data' => $result);
    }

    public function detail($id)
    {
        $idea = IdeaHub::find($id);

        return view('idea_hub/detail', compact('idea'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function getDetail($id)
    {
        $idea = IdeaHub::join('users as u', 'tb_idea_hub.nik', 'u.nik')
            ->select('ide', 'konsep_bisnis', 'referensi', 'divisi','posisi','u.name', 'tb_idea_hub.created_at')
            ->where('tb_idea_hub.id', $id)->first();

        return $idea;
    }



}