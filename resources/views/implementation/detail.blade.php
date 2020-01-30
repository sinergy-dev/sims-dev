@extends('template.template_admin-lte')
@section('content')

    <section class="content-header">
        <h1>
            Detail Project
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Project Implementation</li>
            <li class="active">Detail</li>
        </ol>
    </section>

    <section class="content">

        <input type="text" value="{{ $imp_id }}" id="imp_id" name="imp_id" hidden>

        <!-- <h1>
            @foreach($gantt as $g)
                {{ $g->id }}
            @endforeach
        </h1> -->

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    
                </h3>
        
                @if(Auth::User()->id_position == 'ENGINEER MANAGER' || optional($current_engineer)->role == 'Project Leader')
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
            </div>
            
            <div class="box-body">
                <div class="progress">
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
                                            @if($detail->current_phase == $dips->phase_status)
                                                <b><p style="color:brown;">on progress...</p></b>
                                            @else
                                                <b><p style="color:brown;">(not done)</p></b>
                                            @endif
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
                </table>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Engineer Progress
                </h3>
        
                <div class="box-tools pull-right">
                    @if($detail->current_phase == 'Done')
                    @else
                        @if(Auth::User()->nik == optional($current_engineer)->nik)
                            <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#progress_add" style="width: 75px;"><i class="fa fa-plus"></i>&nbsp Progress</button>
                            <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#problem_add" style="width: 75px"><i class="fa fa-plus"></i>&nbsp Problem</button>
                        @else
                            <button class="btn btn-xs btn-primary disabled" style="width: 75px;"><i class="fa fa-plus"></i>&nbsp Progress</button>
                            <button class="btn btn-xs btn-primary disabled" style="width: 75px"><i class="fa fa-plus"></i>&nbsp Problem</button>
                        @endif
                    @endif
                </div>
            </div>
          
            <div class="box-body">
                <div class="nav-tabs-custom active" id="project_tab" role="tabpanel" aria-labelledby="project-tab">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
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
                    </ul>

                    <div class="tab-content">
                        @foreach($phase as $p_parent)
                            @if($p_parent == $detail->current_phase)
                                <div class="tab-pane active" id="{{ $p_parent }}" role="tabpanel" aria-labelledby="{{ $p_parent }}-tab">
                            @else
                                <div class="tab-pane" id="{{ $p_parent }}" role="tabpanel" aria-labelledby="{{ $p_parent }}-tab">
                            @endif
                                    <center><h5><b><span style="background-color: green;color: white"> -- Progress -- </span></b></h5></center>
                                    <table class="table table-bordered table-striped" id="project_{{ $p_parent }}_table">
                                        <thead>
                                            <tr>
                                                <th style="width: 2%"><center>No.</center></th>
                                                <th style="width: 20%"><center>Engineer Name</center></th>
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
                                                        <td><center>{{ $progress->created_at }}</center></td>
                                                        <td>{!! nl2br(e($progress->progress)) !!}</td>
                                                        <td><center>
                                                            @if(Auth::User()->id_position == 'ENGINEER MANAGER' || $progress->nik == Auth::User()->nik)
                                                                @if($progress->current_phase == 'Done')
                                                                    No Action
                                                                @else
                                                                    <button class="btn btn-xs btn-primary btn-edit" value="{{$progress->id}}" style="width: 40px"><i class="fa fa-pencil"></i></button>
                                                                    <a href="{{ url('progress_delete', $progress->id) }}">
                                                                        <button class="btn btn-xs btn-danger" style="vertical-align: top; width: 40px" onclick="return confirm('Are you sure want to delete this Progress?')">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                No Action
                                                            @endif
                                                        </center></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <center><h5><b><span style="background-color: red;color: white"> -- Problem -- </span></b></h5></center>
                                    <table class="table table-bordered table-striped" id="problem_{{ $p_parent }}_table">
                                        <thead>
                                            <tr>
                                                <th style="width: 2%"><center>No.</center></th>
                                                <th style="width: 20%"><center>Engineer Name</center></th>
                                                <th style="width: 12%"><center>Date</center></th>
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
                                                        <td><center>{{ $problem->start_date }} - {{ $problem->end_date }}</center></td>
                                                        <td>{!! nl2br(e($problem->problem)) !!}</td>
                                                        <td>{!! nl2br(e($problem->conture_measure)) !!}</td>
                                                        <td>{!! nl2br(e($problem->root_cause)) !!}</td>
                                                        <td><center>
                                                            @if(Auth::User()->id_position == 'ENGINEER MANAGER' || $problem->nik == Auth::User()->nik)
                                                                @if($progress->current_phase == 'Done')
                                                                    No Action
                                                                @else
                                                                    <!-- <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#problem_edit" onclick="edit_problem('{{$problem->id}}','{{$problem->start_date}}','{{$problem->end_date}}')" style="width: 40px"><i class="fa fa-pencil"></i></button> -->
                                                                    <button class="btn btn-xs btn-primary btn-edit-prob" value="{{$problem->id}}" style="width: 40px"><i class="fa fa-pencil"></i></button>
                                                                    <a href="{{ url('problem_delete', $problem->id) }}">
                                                                        <button class="btn btn-xs btn-danger" style="vertical-align: top; width: 40px" onclick="return confirm('Are you sure want to delete this Problem?')">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                No Action
                                                            @endif
                                                        </center></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Gantt Chart    
                </h3>
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
                    @if($detail->current_phase == 'Done')
                    @else    
                        @if(Auth::User()->id_position == 'ENGINEER MANAGER')
                            <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#engineer_add" style="width: 75px;"><i class="fa fa-plus"> </i>&nbsp Engineer</button>
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
                            @if(Auth::User()->id_position == 'ENGINEER MANAGER')
                                @if($detail->current_phase == 'Done')
                                @else
                                    <th style="width: 10%"><center>Action</center></th>
                                @endif
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no_engineer_list = 2; ?>
                        <tr>
                            <td>1</td>
                            <td>{{ optional($project_leader)->name }}</td>
                            <td><center>Project Leader</center></td>
                            @if(Auth::User()->id_position == 'ENGINEER MANAGER')
                                @if($detail->current_phase == 'Done')
                                @else
                                    <td><center>
                                        <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#leader_update" style="width: 40px"><i class="fa fa-pencil"></i></button>
                                    </center></td>
                                @endif
                            @endif
                        </tr>
                        @foreach($member as $members)
                            <tr>
                                <td>{{ $no_engineer_list++ }}</td>
                                <td>{{ optional($members)->name }}</td>
                                <td><center>Member</center></td>
                                @if(Auth::User()->id_position == 'ENGINEER MANAGER')
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
                        <?php $no_change_log = 1; ?>
                        @foreach($change_log as $log)
                            <tr>
                                <td>{{ $no_change_log++ }}</td>
                                <td>{{ $log->status }}</td>
                                <td><center>
                                    <?php
                                        $date = substr($log->created_at, 0, 10);
                                        $day = date("l", strtotime($date));
                                        $newDate = date("M, d, Y", strtotime($date));
                                    ?>
                                    {{ $day }} <br> {{ $newDate }}
                                </center></td>
                                <td><center>{{ $log->name }}</center></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{--  Edit Phase  --}}
        <div class="modal fade" id="edit_phase_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{url('/implementation/edit_phase')}}" id="edit_phase_form" name="edit_phase_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Implementation (Edit)</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="edit_phase_id_imp" id="edit_phase_id_imp" value="{{ $imp_id }}" hidden>
                                @foreach($detail_id_engineer as $die)
                                    @if($die->nik == Auth::User()->nik)
                                        <input type="text" value="{{ $die->id }}" name="edit_phase_id_engineer" id="edit_phase_id_engineer" hidden>
                                    @endif
                                @endforeach
                                <center><label>- Phase -</label></center><br>
                                <?php $nou = 1; ?>
                                @foreach($detail_id_phase as $dips)
                                    <?php  
                                        $date1 = $dips->end_date;
                                        $date2 = $dips->finish_date;
                                        
                                        $diff = abs(strtotime($date2) - strtotime($date1));

                                        $years = floor($diff / (365*60*60*24));
                                        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                                    ?>
                                    @if($dips->finish_date == null)
                                        <div>
                                            <div class="col-sm-4" align="left">
                                                <label> {{ $dips->phase_status }} </label>
                                            </div>
                                            <div class="col-sm-4" align="center">
                                                <center>
                                                    @if($detail->current_phase == $dips->phase_status)
                                                        <b><p style="color:brown;">on progress...</p></b>
                                                    @else
                                                        <b><p style="color:brown;">(not done)</p></b>
                                                    @endif
                                                </center>
                                            </div>
                                        </div>
                                    @else
                                        <div>
                                            <div class="col-sm-4" align="left">
                                                <label> {{ $dips->phase_status }} </label>
                                            </div>
                                            <div class="col-sm-4" align="center">
                                                <center>
                                                    @if($dips->end_date > $dips->finish_date)
                                                        <b><p style="color:green;">(-{{ $days }} day)</p></b>
                                                    @elseif($dips->end_date < $dips->finish_date)
                                                        <b><p style="color:red;">(+{{ $days }} day)</p></b>
                                                    @else
                                                        <b><p style="color:blue;">(on time)</p></b>
                                                    @endif
                                                </center>
                                            </div>
                                            <div class="col-sm-4" align="right">
                                                <label>(Date End: {{ $dips->finish_date }})</label>
                                            </div>
                                        </div>
                                    @endif
                                    <?php
                                        $start_date_edit = $dips->start_date;
                                        $end_date_edit = $dips->end_date;

                                        $date_start = str_replace('-', '/', $start_date_edit );
                                        $StartDate = date("m/d/Y", strtotime($date_start));

                                        $date_end = str_replace('-', '/', $end_date_edit );
                                        $EndDate = date("m/d/Y", strtotime($date_end));
                                    ?>
                                    <?php $ulang = $nou++;?>
                                    @if($detail->current_phase == 'Design')
                                        @if($ulang < 2)
                                            <input type="text" class="form-control" name="{{$dips->phase_status}}_date_edit" value="{{$StartDate}} - {{$EndDate}}" readonly><br>
                                        @else
                                            <input type="text" class="form-control" id="{{$dips->phase_status}}_date_edit" name="{{$dips->phase_status}}_date_edit" value="{{$StartDate}} - {{$EndDate}}" required><br>
                                        @endif
                                    @elseif($detail->current_phase == 'Staging')
                                        @if($ulang < 3)
                                            <input type="text" class="form-control" name="{{$dips->phase_status}}_date_edit" value="{{$StartDate}} - {{$EndDate}}" readonly><br>
                                        @else
                                            <input type="text" class="form-control" id="{{$dips->phase_status}}_date_edit" name="{{$dips->phase_status}}_date_edit" value="{{$StartDate}} - {{$EndDate}}" required><br>
                                        @endif
                                    @elseif($detail->current_phase == 'Implementation')
                                        @if($ulang < 4)
                                            <input type="text" class="form-control" name="{{$dips->phase_status}}_date_edit" value="{{$StartDate}} - {{$EndDate}}" readonly><br>
                                        @else
                                            <input type="text" class="form-control" id="{{$dips->phase_status}}_date_edit" name="{{$dips->phase_status}}_date_edit" value="{{$StartDate}} - {{$EndDate}}" required><br>
                                        @endif
                                    @elseif($detail->current_phase == 'Migration')
                                        @if($ulang < 5)
                                            <input type="text" class="form-control" name="{{$dips->phase_status}}_date_edit" value="{{$StartDate}} - {{$EndDate}}" readonly><br>
                                        @else
                                            <input type="text" class="form-control" id="{{$dips->phase_status}}_date_edit" name="{{$dips->phase_status}}_date_edit" value="{{$StartDate}} - {{$EndDate}}" required><br>
                                        @endif
                                    @elseif($detail->current_phase == 'Testing')
                                        @if($ulang < 6)
                                            <input type="text" class="form-control" name="{{$dips->phase_status}}_date_edit" value="{{$StartDate}} - {{$EndDate}}" readonly><br>
                                        @else
                                            <input type="text" class="form-control" id="{{$dips->phase_status}}_date_edit" name="{{$dips->phase_status}}_date_edit" value="{{$StartDate}} - {{$EndDate}}" required><br>
                                        @endif
                                    @endif
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
                <form method="POST" action="{{url('/implementation/update_phase')}}" id="next_phase_form" name="next_phase_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Next Phase</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="next_phase_id_imp" id="next_phase_id_imp" value="{{ $imp_id }}" hidden>
                                <!-- for manager -->
                                @foreach($detail_id_engineer as $die)
                                    @if($die->nik == Auth::User()->nik)
                                        <input type="text" value="{{ $die->id }}" name="update_phase_id_engineer" id="update_phase_id_engineer" hidden>
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
                <form method="POST" action="{{url('/implementation/engineer_progress')}}" id="progress_add_form" name="progress_add_form">
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
                            <input type="text" value="{{ $detail->id }}" name="progress_id_imp" id="progress_id_imp" hidden>
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
                <form method="POST" action="{{url('/implementation/engineer_problem')}}" id="problem_add_form" name="problem_add_form">
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
                            <input type="text" value="{{ $detail->id }}" name="problem_id_imp" id="problem_id_imp" hidden>
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
                <form method="POST" action="{{url('/implementation/engineer_progress_edit')}}" id="progress_edit_form" name="progress_edit_form">
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
                            <input type="text" value="{{ $detail->id }}" name="progress_id_imp" id="progress_id_imp" hidden>
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

        {{-- Problem Edit --}}
        <div class="modal fade" id="problem_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{url('/implementation/engineer_problem_edit')}}" id="problem_edit_form" name="problem_edit_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Engineer Problem (Edit)</h4>
                        </div>
                        <div class="modal-body">
                            <input type="text" id="id_problem" name="id_problem" hidden>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" value="{{ $detail->title }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Current Phase</label>
                                <input type="text" class="form-control" value="{{ $detail->current_phase }}" readonly>
                            </div>
                            <input type="text" value="{{ $detail->id }}" name="problem_id_imp" id="problem_id_imp" hidden>
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
                                <input type="text" class="form-control" id="problem_date_edit" name="problem_date_edit">
                            </div>
                            <div class="form-group">
                                <label>Problem</label>
                                <textarea class="form-control" name="problem_input_edit" id="problem_input_edit" cols="30" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Conture Measure</label>
                                <textarea class="form-control" name="measure_input_edit" id="measure_input_edit" cols="30" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Root Cause</label>
                                <textarea class="form-control" name="cause_input_edit" id="cause_input_edit" cols="30" rows="5"></textarea>
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
                                <select data-live-search="true" name="project_member[]" id="project_member" class="form-control selectpicker" required multiple>
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
                <form method="POST" action="{{url('/implementation/update_leader')}}" id="leader_update_form" name="leader_update_form">
                {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">Change Project Leader</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="leader_update_id_imp" id="leader_update_id_imp" value="{{ $imp_id }}" hidden>
                                <select data-live-search="true" name="project_leader" id="project_leader" class="form-control selectpicker" required>
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

    </section>

@endsection

@section('script')

    <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
    <script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

    <script type="text/javascript">

        gantt.config.date_format = "%Y-%m-%d %H:%i:%s";
        
        gantt.init("gantt_here");
        
        gantt.load("{{url('/data',$imp_id)}}");

        var dp = new gantt.dataProcessor("{{url('api/implementation',$imp_id)}}");
        dp.init(gantt);
        dp.setTransactionMode("REST");

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

        $('input[id="Design_date_edit"]').daterangepicker();
        $('input[id="Staging_date_edit"]').daterangepicker();
        $('input[id="Implementation_date_edit"]').daterangepicker();
        $('input[id="Migration_date_edit"]').daterangepicker();
        $('input[id="Testing_date_edit"]').daterangepicker();

        $('input[id="problem_date"]').daterangepicker();
        $('input[id="problem_date_edit"]').daterangepicker();

        // function edit(id, phase_status){
        //     $('#id_progress').val(id);
        //     $('#phase_edit').val(phase_status);
        // }

        $('.btn-edit').click(function(){
            $.ajax({
              type:"GET",
              url:'/imp/getprogress',
              data:{
                id_pro:this.value,
              },
              success: function(result){
                $.each(result[0], function(key, value){
                    $('#id_progress').val(value.id);
                    $('#phase_edit').val(value.phase_status);
                    $('#edit_progress_input').val(value.progress);
                });

              }
            }); 
            $("#progress_edit").modal("show");
        });

        $('.btn-edit-prob').click(function(){
            $.ajax({
              type:"GET",
              url:'/imp/getproblem',
              data:{
                id_prog:this.value,
              },
              success: function(result){
                $.each(result[0], function(key, value){

                    $('#id_problem').val(value.id);
                    $('#problem_date_edit').val(moment(value.start_date).format('MM/DD/YYYY') + ' - ' + moment(value.end_date).format('MM/DD/YYYY'));
                    $('#problem_input_edit').val(value.problem);
                    $('#measure_input_edit').val(value.conture_measure);
                    $('#cause_input_edit').val(value.root_cause);
                });

              }
            }); 
            $("#problem_edit").modal("show");
        });

        // function edit_problem(id, start_date, end_date){
        //     var date_start = start_date.replace('-', '/');
        //     var date_start_2 = date_start.replace('-', '/');

        //     $('#id_problem').val(id);
        //     $('#problem_date_edit').val(date_start_2);
        // }

    </script>

@endsection