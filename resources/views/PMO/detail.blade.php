@extends('template.main')
@section('head_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="codebase/dhtmlxgantt.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
<style type="text/css">
    .select2{
        width:100%!important;
    }
    .selectpicker{
        width:100%!important;
    }
</style>
@endsection
@section('content')
    <section class="content-header">
        <h1>
            Detail Project / {{$imp_id}}
        </h1>
        @if($id == NULL)
        @else
        <div style="background: red"><h5 style="padding: 16px;color: white">{{$detail->title}}</h5></div>
        @endif
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><a href="{{url('PMO/index')}}">Project Manager</a></li>
            <li class="active">Detail</li>
        </ol>
    </section>

    <section class="content">

        <input type="text" value="{{ $imp_id }}" id="pmo_lead" name="pmo_lead" hidden>
        <input type="text" value="{{ $detail->id_pmo }}" id="pmo_id" name="pmo_id" hidden>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    
                </h3>
                @if($id == NULL)
                <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#project_add" style="width: 75px;"><i class="fa fa-plus"> </i>&nbsp Project</button>
                @else
                @if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'PMO' || optional($current_engineer)->role == 'Project Leader')
                @if($detail->current_phase == 'Done')
                @else
                    <button class="btn btn-xs btn-primary pull-left" data-toggle="modal" data-target="#edit_phase_modal" style="width: 90px"><i class="fa fa-pencil"></i>&nbsp Edit Phase</button>
                    @if($detail->current_phase == 'Testing')
                        <button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#next_phase_modal" style="width: 70px"><i class="fa fa-check"></i>&nbsp Done</button>
                    @else
                        <button class="btn btn-xs btn-warning pull-right" data-toggle="modal" data-target="#next_phase_modal" style="width: 90px"><i class="fa fa-forward"></i>&nbsp Next Phase</button>
                    @endif
                @endif
	                @endif    
                @endif
            </div>
            
            <div class="box-body">
                <div class="progress">
                    @if($id == NULL)
                     <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                0%
                            </div>
                    @else
                        @if($detail->current_phase == 'Design')
                            <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                                10%
                            </div>
                        @elseif($detail->current_phase == 'Staging')
                            <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width:30%">
                                30%
                            </div>
                        @elseif($detail->current_phase == 'Implementation')
                            <div class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%">
                                50%
                            </div>
                        @elseif($detail->current_phase == 'Migration')
                            <div class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                                70%
                            </div>
                        @elseif($detail->current_phase == 'Testing')
                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:90%">
                                90%
                            </div>
                        @elseif($detail->current_phase == 'Done')
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                Complete
                            </div>
                        @endif
                    @endif
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 20%"><center>Design</center></th>
                            <th style="width: 20%"><center>Staging</center></th>
                            <th style="width: 20%"><center>Implementation</center></th>
                            <th style="width: 20%"><center>Migration</center></th>
                            <th style="width: 20%"><center>Testing</center></th>
                        </tr>
                    </thead>
                    @if($id == NULL)
                    @else
                    <tbody>
                        <tr>
                            @foreach($detail_id_phase as $dips)
                                <td>
                                    <center>
                                        {{ $dips->start_date }} - {{ $dips->end_date }}
                                    </center>
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th colspan="5"><center>Finish Date</center></th>
                        </tr>
                        <tr>
                            @foreach($detail_id_phase as $dips)
                                <td>
                                    <?php  
                                        $date1 = $dips->end_date;
                                        $date2 = $dips->finish_date;
                                        
                                        $diff = abs(strtotime($date2) - strtotime($date1));

                                        $years = floor($diff / (365*60*60*24));
                                        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                                    ?>
                                    <center>{{ $dips->finish_date }}
                                        @if($dips->finish_date == null)
                                            <b><p style="color:brown;">(not done)</p></b>
                                        @else
                                            @if($dips->end_date > $dips->finish_date)
                                                <b><p style="color:green;">(-{{ $days }} day)</p></b>
                                            @elseif($dips->end_date < $dips->finish_date)
                                                <b><p style="color:red;">(+{{ $days }} day)</p></b>
                                            @else
                                                <b><p style="color:blue;">(on time)</p></b>
                                            @endif
                                        @endif
                                    </center>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                    @endif
                </table>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Engineer Progress
                </h3>
        
                <div class="box-tools pull-right">
                    @if($id == NULL)
                    @else
                        @if($detail->current_phase == 'Done')
                        @else
                            @if(Auth::User()->nik == optional($current_engineer)->nik || Auth::User()->nik == $project_leader->nik)
                                <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#progress_add" style="width: 75px;"><i class="fa fa-plus"></i>&nbsp Progress</button>
                                <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#problem_add" style="width: 75px"><i class="fa fa-plus"></i>&nbsp Problem</button>
                            @else
                                <button class="btn btn-xs btn-primary disabled" style="width: 75px;"><i class="fa fa-plus"></i>&nbsp Progress</button>
                                <button class="btn btn-xs btn-primary disabled" style="width: 75px"><i class="fa fa-plus"></i>&nbsp Problem</button>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
          
            <div class="box-body">
                <div class="nav-tabs-custom active" id="project_tab" role="tabpanel" aria-labelledby="project-tab">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @if($id == NULL)
                        @else

                            @foreach($phase as $p_parent)
                                @if($p_parent == $detail->current_phase)
                                    <li class="nav-item active">
                                        <a class="nav-link active" id="{{ $p_parent }}-tab" data-toggle="tab" href="#{{ $p_parent }}" role="tab" aria-controls="{{ $p_parent }}" aria-selected="true">
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link" id="{{ $p_parent }}-tab" data-toggle="tab" href="#{{ $p_parent }}" role="tab" aria-controls="{{ $p_parent }}" aria-selected="true">
                                @endif
                                            {{ $p_parent }}
                                        </a>
                                    </li>
                            @endforeach
                        @endif
                    </ul>

                    <div class="tab-content">
                        @if($id == NULL)
                        @else
                            @foreach($phase as $p_parent)
                                @if($p_parent == $detail->current_phase)
                                    <div class="tab-pane active" id="{{ $p_parent }}" role="tabpanel" aria-labelledby="{{ $p_parent }}-tab">
                                @else
                                    <div class="tab-pane" id="{{ $p_parent }}" role="tabpanel" aria-labelledby="{{ $p_parent }}-tab">
                                @endif
                                        <center><h5><b><span style="background-color: green;color: white"> -- Progress Table -- </span></b></h5></center>
                                        <table class="table table-bordered table-striped" id="project_{{ $p_parent }}_table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 2%"><center>No.</center></th>
                                                    <th style="width: 30%"><center>Engineer Name</center></th>
                                                    <th style="width: 10%"><center>Date</center></th>
                                                    <th><center>Progress</center></th>
                                                    <th style="width: 10%"><center>Action</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no_design = 1; ?>
                                                @foreach($engineer_progress as $progress)
                                                    @if($progress->phase_status == $p_parent)
                                                    <tr>
                                                        <td>{{ $no_design++ }}</td>
                                                        <td>{{ $progress->name }}</td>
                                                        <td><center>{{ $progress->date }}</center></td>
                                                        <td>{!! nl2br(e($progress->progress)) !!}</td>
                                                        <td><center>
                                                            @if(Auth::User()->id_position == 'MANAGER' || $progress->nik == Auth::User()->nik)
                                                                <button class="btn btn-xs btn-primary btn-edit" value="{{$progress->id}}" style="width: 40px"><i class="fa fa-pencil" ></i></button>
                                                                <a href="{{ url('progress_delete', $progress->id) }}">
                                                                    <button class="btn btn-xs btn-danger" style="vertical-align: top; width: 40px" onclick="return confirm('Are you sure want to delete this Progress?')">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </a>
                                                            @else
                                                                No Action
                                                            @endif
                                                        </center></td>
                                                    </tr>
                                                    @endif
                                                @endforeach

                                              <!--   @foreach($engineer_progress as $progress)
                                                    @if($progress->phase_status == $detail->current_phase)
                                                        <tr>
                                                            <td>{{ $no_design++ }}</td>
                                                            <td>{{ $progress->name }}</td>
                                                            <td><center>{{ $progress->date }}</center></td>
                                                            <td>{!! nl2br(e($progress->progress)) !!}</td>
                                                            <td><center>
                                                                @if(Auth::User()->id_position == 'MANAGER' || $progress->nik == Auth::User()->nik)
                                                                    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#progress_edit" onclick="edit('{{$progress->id}}','{{$progress->phase_status}}')" style="width: 40px"><i class="fa fa-pencil"></i></button>
                                                                    <a href="{{ url('progress_delete', $progress->id) }}">
                                                                        <button class="btn btn-xs btn-danger" style="vertical-align: top; width: 40px" onclick="return confirm('Are you sure want to delete this Progress?')">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </a>
                                                                @else
                                                                    No Action
                                                                @endif
                                                            </center></td>
                                                        </tr>
                                                    @endif
                                                @endforeach -->
                                            </tbody>
                                        </table>
                                        <center><h5><b><span style="background-color: red;color: white"> -- Problem Table -- </span></b></h5></center>
                                        <table class="table table-bordered table-striped" id="problem_{{ $p_parent }}_table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 2%"><center>No.</center></th>
                                                    <th style="width: 30%"><center>Engineer Name</center></th>
                                                    <th><center>Date</center></th>
                                                    <th style=""><center>Problem</center></th>
                                                    <th style=""><center>Conture Measure</center></th>
                                                    <th style=""><center>Root Cause</center></th>
                                                    <th style="width: 10%"><center>Action</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no_problem = 1; ?>
                                                @foreach($engineer_problem as $problem)
                                                    @if($problem->phase_status == $p_parent)
                                                        <tr>
                                                            <td>{{ $no_problem++ }}</td>
                                                            <td>{{ $problem->name }}</td>
                                                            <td>{{ date('d/m/Y', strtotime($problem->start_date))}} - {{ date('d/m/Y', strtotime($problem->end_date ))}}</td>
                                                            <td>{{ $problem->problem }}</td>
                                                            <td>{{ $problem->conture_measure }}</td>
                                                            <td>{{ $problem->root_cause }}</td>
                                                            <td></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Gantt Chart    
                </h3>
                <div class="pull-right">
                    <button class="btn btn-sm btn-primary disabled" style="width: 75px;"><i class="fa fa-upload"></i>&nbsp Import</button>&nbsp
                    <button class="btn btn-sm btn-success disabled" style="width: 75px;"><i class="fa fa-download"></i><a href="{{url('/exportGantt')}}"></a>&nbsp Export</button>
                </div>
            </div>

            <div class="box-body">
                <div id="gantt_here" style='width:100%; height:650px;'></div>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Engineer List
                </h3>
        
                <div class="box-tools pull-right">
                    @if($id == NULL)
                    @else
                        @if($detail->current_phase == 'Done')
                        @else    
                            @if(Auth::User()->id_position == 'MANAGER')
                                <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#engineer_add" style="width: 75px;"><i class="fa fa-plus"> </i>&nbsp Engineer</button>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        
            <div class="box-body">
                <table class="table table-bordered table-striped" id="engineer_assign_table">
                    <thead>
                        <tr>
                            <th style="width: 2%"><center>No.</center></th>
                            <th style=""><center>Engineer Name</center></th>
                            <th style="width: 13%"><center>As</center></th>
                            @if($id == NULL)
                            @else
                                @if($detail->current_phase == 'Done')
                                @else
                                    @if(Auth::User()->id_position == 'MANAGER')
                                        <th style="width: 10%"><center>Action</center></th>
                                    @else
                                    @endif
                                @endif
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no_engineer_list = 2; ?>
                        @if($id == NULL)
                        @else
                            <tr>
                                <td>1</td>
                                <td>{{ optional($project_leader)->name }}</td>
                                <td><center>Project Leader</center></td>
                                @if($id == NULL)
                                @else
                                    @if(Auth::User()->id_position == 'MANAGER')
                                        @if($detail->current_phase == 'Done')
                                        @else
                                            <td><center>
                                                <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#leader_update" style="width: 40px"><i class="fa fa-pencil"></i></button>
                                            </center></td>
                                        @endif
                                    @endif
                                @endif
                            </tr>
                        @endif
                        @if($id == NULL)
                        @else
                            @foreach($member as $members)
                                <tr>
                                    <td>{{ $no_engineer_list++ }}</td>
                                    <td>{{ optional($members)->name }}</td>
                                    <td><center>Member</center></td>
                                    @if(Auth::User()->id_position == 'MANAGER')
                                        @if($detail->current_phase == 'Done')
                                        @else
                                            <td><center>
                                                <a href="{{ url('engineer_delete', $members->id) }}">
                                                    <button class="btn btn-xs btn-danger" style="vertical-align: top; width: 40px" onclick="return confirm('Are you sure want to delete this Engineer? (Seluruh progress engineer ini akan dihapus secara PERMANEN)')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </a>
                                            </center></td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Change Log
                </h3>
        
                <div class="box-tools pull-right">

                </div>
            </div>
        
            <div class="box-body">
                <table class="table table-bordered table-striped" id="change_log_table">
                    <thead>
                        <tr>
                            <th style="width: 2%"><center>No.</center></th>
                            <th style=""><center>Status</center></th>
                            <th style="width: 13%"><center>Created At</center></th>
                            <th style="width: 10%"><center>By</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($id == NULL)
                        @else
                            <?php $no_change_log = 1; ?>
                            @foreach($change_log as $log)
                                <tr>
                                    <td>{{ $no_change_log++ }}</td>
                                    <td>{{ $log->status }}</td>
                                    <td><center>{{ $log->date }}</center></td>
                                    <td><center>{{ $log->name }}</center></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if($id == NULL)
        @else

        {{--  Edit Phase  --}}
        <div class="modal fade" id="edit_phase_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{url('edit/phase')}}" id="edit_phase_form" name="edit_phase_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Implementation (Edit)</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="edit_phase_id_imp" id="edit_phase_id_imp" value="{{ $detail->id_pmo }}" hidden>
                                @foreach($detail_id_engineer as $die)
                                    @if($die->nik == Auth::User()->nik)
                                        <input type="text" value="{{ $die->id }}" name="edit_phase_id_engineer" id="edit_phase_id_engineer" hidden>
                                    @endif
                                @endforeach
                                <center><label>- Phase -</label></center>
                                @foreach($detail_id_phase as $dips)
                                    <label>{{ $dips->phase_status }}</label>
                                    <?php
                                        $start_date_edit = $dips->start_date;
                                        $end_date_edit = $dips->end_date;

                                        $date_start = str_replace('-', '/', $start_date_edit );
                                        $StartDate = date("m/d/Y", strtotime($date_start));

                                        $date_end = str_replace('-', '/', $end_date_edit );
                                        $EndDate = date("m/d/Y", strtotime($date_end));
                                    ?>
                                    <input type="text" class="form-control" id="{{$dips->phase_status}}_date_edit" name="{{$dips->phase_status}}_date_edit" value="{{$StartDate}} - {{$EndDate}}" required>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 15%" id="checkBtnAdd">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Next Phase  --}}
        <div class="modal fade" id="next_phase_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{url('update/phase')}}" id="next_phase_form" name="next_phase_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Next Phase</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="next_phase_id_imp" id="next_phase_id_imp" value="{{ $detail->id_pmo }}" hidden>
                                @foreach($detail_id_engineer as $die)
                                    @if($die->nik == Auth::User()->nik)
                                        <input type="text" value="{{ $die->id }}" name="update_phase_id_engineer" id="update_phase_id_engineer">
                                    @endif
                                @endforeach
                                <label>Next Phase</label>
                                @if($detail->current_phase == 'Design')
                                    <input type="text" class="form-control" value="Staging" id="next_current_phase" name="next_current_phase" readonly>
                                @elseif($detail->current_phase == 'Staging')
                                    <input type="text" class="form-control" value="Implementation" id="next_current_phase" name="next_current_phase" readonly>
                                @elseif($detail->current_phase == 'Implementation')
                                    <input type="text" class="form-control" value="Migration" id="next_current_phase" name="next_current_phase" readonly>
                                @elseif($detail->current_phase == 'Migration')
                                    <input type="text" class="form-control" value="Testing" id="next_current_phase" name="next_current_phase" readonly>
                                @elseif($detail->current_phase == 'Testing')
                                    <input type="text" class="form-control" value="Done" id="next_current_phase" name="next_current_phase" readonly>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Current Phase</label>
                                <input type="text" class="form-control" value="{{ $detail->current_phase }}" name="phase_now" id="phase_now" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 15%" id="checkBtnAdd">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Progress Add  --}}
        <div class="modal fade" id="progress_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{url('add_progress')}}" id="progress_add_form" name="progress_add_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Engineer Progress</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" value="{{ $detail->title }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Current Phase</label>
                                <input type="text" class="form-control" value="{{ $detail->current_phase }}" readonly>
                            </div>
                            <input type="text" value="{{ $detail->id_pmo }}" name="progress_id_imp" id="progress_id_imp" hidden>
                            @foreach($detail_id_engineer as $die)
                                @if($die->nik == Auth::User()->nik)
                                    <input type="text" value="{{ $die->id }}" name="progress_id_engineer" id="progress_id_engineer" hidden>
                                @endif
                            @endforeach
                            @foreach($detail_id_phase as $dip)
                                @if($dip->phase_status == $detail->current_phase)
                                    <input type="text" value="{{ $dip->id }}" name="progress_id_phase" id="progress_id_phase" hidden>
                                @endif
                            @endforeach
                            <div class="form-group">
                                <label>Progress</label>
                                <textarea class="form-control" name="progress_input" id="progress_input" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 15%" id="checkBtnAdd">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Problem Add  --}}
        <div class="modal fade" id="problem_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{url('/add/problem')}}" id="problem_add_form" name="problem_add_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Engineer Problem</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" value="{{ $detail->title }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Current Phase</label>
                                <input type="text" class="form-control" value="{{ $detail->current_phase }}" readonly>
                            </div>
                            <input type="text" value="{{ $detail->id_pmo }}" name="problem_id_imp" id="problem_id_imp" hidden>
                            @foreach($detail_id_engineer as $die)
                                @if($die->nik == Auth::User()->nik)
                                    <input type="text" value="{{ $die->id }}" name="problem_id_engineer" id="problem_id_engineer" hidden>
                                @endif
                            @endforeach
                            @foreach($detail_id_phase as $dip)
                                @if($dip->phase_status == $detail->current_phase)
                                    <input type="text" value="{{ $dip->id }}" name="problem_id_phase" id="problem_id_phase" hidden>
                                @endif
                            @endforeach
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" class="form-control" id="problem_date" name="problem_date">
                            </div>
                            <div class="form-group">
                                <label>Problem</label>
                                <textarea class="form-control" name="problem_input" id="problem_input" cols="30" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Conture Measure</label>
                                <textarea class="form-control" name="measure_input" id="measure_input" cols="30" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Root Cause</label>
                                <textarea class="form-control" name="cause_input" id="cause_input" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 15%" id="checkBtnAdd">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Progress Edit  --}}
        <div class="modal fade" id="progress_edit" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{url('progress/edit')}}" id="progress_edit_form" name="progress_edit_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Engineer Progress (Edit)</h4>
                        </div>
                        <div class="modal-body">
                            <input type="text" id="id_progress" name="id_progress" hidden>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" value="{{ $detail->title }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Phase</label>
                                <input type="text" class="form-control" value="" id="phase_edit" name="phase_edit" readonly>
                            </div>
                            <input type="text" value="{{ $detail->id_pmo }}" name="progress_id_imp" id="progress_id_imp" hidden>
                            @foreach($detail_id_engineer as $die)
                                @if($die->nik == Auth::User()->nik)
                                    <input type="text" value="{{ $die->id }}" name="progress_id_engineer" id="progress_id_engineer" hidden>
                                @endif
                            @endforeach
                            @foreach($detail_id_phase as $dip)
                                @if($dip->phase_status == $detail->current_phase)
                                    <input type="text" value="{{ $dip->id }}" name="progress_id_phase" id="progress_id_phase" hidden>
                                @endif
                            @endforeach
                            <div class="form-group">
                                <label>Progress</label>
                                <textarea class="form-control" name="edit_progress_input" id="edit_progress_input" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 15%" id="checkBtnAdd">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Engineer Add  --}}
        <div class="modal fade" id="engineer_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{url('/implementation/update_engineer')}}" id="engineer_add_form" name="engineer_add_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Member Add</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="engineer_add_id_imp" id="engineer_add_id_imp" value="{{ $imp_id }}" hidden>
                                <select class="selectpicker" data-live-search="true" name="project_member[]" id="project_member" class="form-control" required multiple>
                                    @foreach($list_engineer as $le)
                                        <option value="{{ $le->nik }}">{{ $le->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 15%" id="checkBtnAdd">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--  Edit Project Leader  --}}
        <div class="modal fade" id="leader_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{url('/update/leader')}}" id="leader_update_form" name="leader_update_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Change Project Leader</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="leader_update_id_imp" id="leader_update_id_imp" value="{{$detail->id_pmo}}">
                                <select class="selectpicker" data-live-search="true" name="project_leader" id="project_leader" class="form-control" required>
                                    <option value="">Nothing selected</option>
                                    <option value="{{ $project_leader->nik }}" selected>{{ $project_leader->name }}</option>
                                    @foreach($member as $engineer)
                                        <option value="{{ $engineer->nik }}">
                                            {{ $engineer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 15%" id="checkBtnAdd">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @endif

        {{-- Add Project Stage --}}
        <div class="modal fade" id="project_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
                <form method="POST" action="{{url('store_phase')}}" id="project_add_form" name="project_add_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Project Manager</h4>
                        </div>
                        <div class="modal-body" >
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <label>Project Title</label>
                                        <input type="text" value="{{ $imp_id }}" id="pmo_lead_add" name="pmo_lead_add" hidden>
                                        <input type="text" class="form-control" id="add_project_title" name="add_project_title" placeholder="Enter title" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><span style="background-color: red;color: white">--Phase--</span></label>
                                <div class="row">
                                    <div class="col-xs-6 col-md-6">
                                        <label>Design</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control" id="design_date" name="design_date" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-6">
                                        <label>Staging</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control" id="staging_date" name="staging_date" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-md-6">
                                        <label>Implementation</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control" id="implementation_date" name="implementation_date" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-6">
                                        <label>Migration</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control" id="migration_date" name="migration_date" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6 col-md-6">
                                        <label>Testing</label>
                                        <div class="input-group date">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                        <input type="text" class="form-control" id="testing_date" name="testing_date" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-6">
                                    </div>
                                </div>
                                                                
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-6 col-md-6">
                                        <label>Project Leader</label><br>
                                        <select class="select" data-live-search="true" name="project_leader" id="project_leader" class="form-control" required>
                                            <option value="">Nothing selected</option>
                                            <option value="{{ $engineer_manager->nik }}">{{ $engineer_manager->name }}</option>
                                            @foreach($engineer_staff as $engineer)
                                                <option value="{{ $engineer->nik }}">{{ $engineer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <label>Member</label><br>
                                        <select class="select" data-live-search="true" name="project_member[]" id="project_member" multiple="multiple" class="form-control" multiple required>
                                            <option value="{{ $engineer_manager->nik }}">{{ $engineer_manager->name }}</option>
                                            @foreach($engineer_staff as $engineer)
                                                <option value="{{ $engineer->nik }}">{{ $engineer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 15%" id="checkBtnAdd">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </section>

@endsection
@section('scriptImport')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<script src="codebase/dhtmlxgantt.js"></script>
<script src="https://export.dhtmlx.com/gantt/api.js"></script>
@endsection
@section('script')
    <script type="text/javascript">

        gantt.config.xml_date = "%Y-%m-%d %H:%i:%s";
        
        gantt.init("gantt_here");
        gantt.load("{{url('/data_pmo',$detail->id_pmo)}}");

        var dp = new gantt.dataProcessor("{{url('/api/pmo',$detail->id_pmo)}}");
        dp.init(gantt);
        dp.setTransactionMode("REST");

        $('.btn-edit').click(function(){
            $.ajax({
              type:"GET",
              url:'/progress/getprogress',
              data:{
                id_progress:this.value,
              },
              success: function(result){
                $.each(result[0], function(key, value){
                  $('#edit_progress_input').val(value.progress);
                  $('#phase_edit').val(value.phase_status);
                });

              }
            }); 
            $("#progress_edit").modal("show");
        });

        $('#implementation_table').DataTable();

        $('#project_Design_table').DataTable();
        $('#project_Staging_table').DataTable();
        $('#project_Implementation_table').DataTable();
        $('#project_Migration_table').DataTable();
        $('#project_Testing_table').DataTable();

        $('#problem_Design_table').DataTable();
        $('#problem_Staging_table').DataTable();
        $('#problem_Implementation_table').DataTable();
        $('#problem_Migration_table').DataTable();
        $('#problem_Testing_table').DataTable();

        $('#engineer_assign_table').DataTable();
        $('#change_log_table').DataTable();

        $('input[name="Design_date_edit"]').daterangepicker();
        $('input[name="Staging_date_edit"]').daterangepicker();
        $('input[name="Implementation_date_edit"]').daterangepicker();
        $('input[name="Migration_date_edit"]').daterangepicker();
        $('input[name="Testing_date_edit"]').daterangepicker();

        $('input[name="problem_date"]').daterangepicker();

        $('input[name="design_date"]').daterangepicker();
        $('input[name="staging_date"]').daterangepicker();
        $('input[name="implementation_date"]').daterangepicker();
        $('input[name="migration_date"]').daterangepicker();
        $('input[name="testing_date"]').daterangepicker();

        $('project_member').selectpicker();

        $('.select').select2({
            closeOnSelect: false
        });

        // function edit(id, phase_status){
        //     $('#id_progress').val(id);
        //     $('#phase_edit').val(phase_status);
        // }

    </script>

@endsection