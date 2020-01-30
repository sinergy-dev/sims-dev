@extends('template.template')
@section('content')

<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Leaving Permit</a>
        </li>
      </ol>


<div class="card mb-3">
        <div class="card-header">
           <i class="fa fa-table"></i> <b>List of Leaving Permit</b>
           <button type="button" class="btn btn-sd btn-primary pull-right" data-target="#modalCuti" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspPermission</button>
      </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <!-- <th>NIK</th> -->
                  <th>Employees Name</th>
                  <th>Position</th>
                  <th>Division</th>
                  <th>Date Of Request</th>
                  <th>Status</th>
                  @if(Auth::User()->id_position != 'HR MANAGER' && Auth::User()->id_division != 'HR')
                  <th>Action</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @foreach($cuti as $data)
                <tr>
                  <!-- <td>{{$data->nik}}</td> -->
                  <td>{{$data->name}}</td>
                  <td>{{$data->name_position}}</td>
                  <td>{{$data->name_division}}</td>
                  <td>
                    @if(Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'ENGINEER MANAGER' || Auth::User()->id_position == 'DIRECTOR')
                    <a href="" data-target="#detail_cuti" data-toggle="modal" onclick="detil_cuti('{{$data->date_req}}','{{$data->date_start}}','{{$data->date_end}}','{{$data->reason_leave}}')">{{$data->date_req}}</a>
                    @elseif($data->status != NULL)
                    <a href="" data-target="#detail_cuti" data-toggle="modal" onclick="detil_cuti('{{$data->date_req}}','{{$data->date_start}}','{{$data->date_end}}','{{$data->reason_leave}}')">{{$data->date_req}}</a>
                    @else
                    {{$data->date_req}}
                    @endif
                  </td>
                  <td>
                    @if($data->status == 'v')
                     <label class="btn-sm btn-success">Approved</label>
                    @elseif($data->status == 'd')
                     <button class="btn btn-primary btn-danger" data-target="#decline_reason" data-toggle="modal" onclick="decline('{{$data->id_cuti}}', '{{$data->decline_reason}}')">Declined</button>
                    @else
                     <label class="btn-sm btn-warning">Pending</label> 
                    @endif
                  </td>
                  @if(Auth::User()->id_position != 'HR MANAGER' && Auth::User()->id_division != 'HR')
                  <td>
                      @if(Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'ENGINEER MANAGER' || Auth::User()->id_position == 'DIRECTOR')
                        @if($data->status == NULL)
                          <form method="POST" action="{{url('/approve_cuti')}}">
                            @csrf
                            <input value="{{$data->id_cuti}}" name="id_cuti_approve" id="id_cuti_approve" hidden>
                            <button type="submit" class="btn btn-primary btn-success" onclick="return confirm('Approve this Leaving Permit?')">Approve</button>
                          </form>
                          <button class="btn btn-primary btn-danger" data-target="#reason_decline" data-toggle="modal" onclick="decline('{{$data->id_cuti}}', '{{$data->decline_reason}}')">Decline</button>
                        @else
                          <button class="btn btn-primary btn-success" disabled>Approve</button>
                          <button class="btn btn-primary btn-danger" disabled>Decline</button>
                        @endif
                      @else
                        @if($data->status == NULL)
                          <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" style="width: 40px;height: 40px;text-align: center;" data-target="#modalCuti_edit" data-toggle="modal" onclick="edit_cuti('{{$data->id_cuti}}','{{$data->date_start}}','{{$data->date_end}}','{{$data->reason_leave}}')">
                          </button>
                        @else
                          <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" style="width: 40px;height: 40px;text-align: center;" disabled>
                          </button>
                        @endif
                      @endif
                  </td>
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Sinergy Informasi Pratama © 2018</div>
      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <!-- <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Sinergy Informasi Pratama © 2018</small>
        </div>
      </div>
    </footer> -->



    <!--MODAL ADD-->  
    <div class="modal fade" id="modalCuti" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Leaving Permit</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_cuti')}}" id="modalAddCuti" name="modalAddCuti">
            @csrf

            <div class="form-group">
                <label>Date Start</label>
                <input type="date" class="form-control" id="date_start" name="date_start" required>
            </div>

            <div class="form-group">
                <label>Date End</label>
                <input type="date" class="form-control" id="date_end" name="date_end" required>
            </div>

            <div class="form-group">
                <label>Reason For Leave</label>
                <textarea class="form-control" type="text" id="reason" name="reason"></textarea>
            </div>      
             
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
             <!--  <button type="submit" class="btn btn-primary" id="btn-save" value="add"  data-dismiss="modal" >Submit</button>
              <input type="hidden" id="lead_id" name="lead_id" value="0"> -->
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

@foreach($cuti as $data)
@if(Auth::User()->nik == $data->nik)
    <!--MODAL EDIT-->  
<div class="modal fade" id="modalCuti_edit" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Leaving Permit</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_cuti')}}" id="modaleditCuti" name="modaleditCuti">
            @csrf
            <input type="" name="id_cuti" id="id_cuti" value="{{$data->id_cuti}}" hidden>
         <!--    <div class="form-group">
                <label>Date Of Request</label>
                <input type="date" class="form-control" id="date_request_edit" name="date_request_edit" required>
            </div> -->

            <div class="form-group">
                <label>Date Start</label>
                <input type="date" class="form-control" id="date_start_edit" name="date_start_edit" required>
            </div>

            <div class="form-group">
                <label>Date End</label>
                <input type="date" class="form-control" id="date_end_edit" name="date_end_edit" required>
            </div>

            <div class="form-group">
                <label>Reason For Leave</label>
                <textarea class="form-control" type="text" id="reason_edit" name="reason_edit" required></textarea>
            </div>      
             
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
             <!--  <button type="submit" class="btn btn-primary" id="btn-save" value="add"  data-dismiss="modal" >Submit</button>
              <input type="hidden" id="lead_id" name="lead_id" value="0"> -->
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>
@endif
@endforeach


<!--Modal Detail-->
<div class="modal fade" id="detail_cuti" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Leaving Permit</h4>
        </div>
        <div class="modal-body">
          <form id="detail_cuti" name="detail_cuti">
            @csrf
            <div class="form-group">
                <label>Date Of Request</label>
                <input type="date" class="form-control" id="date_request_detil" name="date_request_detil" readonly>
            </div>

            <div class="form-group">
                <label>Date Start</label>
                <input type="date" class="form-control" id="date_start_detil" name="date_start_detil" readonly>
            </div>

            <div class="form-group">
                <label>Date End</label>
                <input type="date" class="form-control" id="date_end_detil" name="date_end_detil" readonly>
            </div>

            <div class="form-group">
                <label>Reason For Leave</label>
                <textarea class="form-control" type="text" id="reason_detil" name="reason_detil" readonly></textarea>
            </div>      
             
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
             <!--  <button type="submit" class="btn btn-primary" id="btn-save" value="add"  data-dismiss="modal" >Submit</button>
              <input type="hidden" id="lead_id" name="lead_id" value="0"> -->
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>


 <div class="modal fade" id="reason_decline" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Decline Information</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/decline_cuti')}}" id="reason_decline" name="reason_decline">
            @csrf
          <input type="" name="id_cuti_decline" id="id_cuti_decline" hidden>
          <div class="form-group">
            <label for="sow">Decline reason</label>
            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
          </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Decline</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

 <div class="modal fade" id="decline_reason" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Decline Information</h4>
        </div>
        <div class="modal-body">
          <form method="" action="" id="decline_reason" name="decline_reason">
            @csrf
          <div class="form-group">
            <label for="sow">Decline reason</label>
            <textarea name="keterangan_decline" id="keterangan_decline" class="form-control" readonly></textarea>
          </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button><!-- 
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Decline</button> -->
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

@endsection

@section('script')
  <script type="text/javascript">
    $('#datatables').DataTable( {
        "scrollX": true
        } );
    function edit_cuti(id_cuti,date_start,date_end,reason_leave){
      $("#id_cuti").val(id_cuti);
      $("#date_start_edit").val(date_start);
      $("#date_end_edit").val(date_end);
      $("#reason_edit").val(reason_leave);
    }

    function detil_cuti(date_req,date_start,date_end,reason_leave){
      $("#date_request_detil").val(date_req);
      $("#date_start_detil").val(date_start);
      $("#date_end_detil").val(date_end);
      $("#reason_detil").val(reason_leave);
    }

    function decline(id_cuti,decline_reason){
      $("#id_cuti_decline").val(id_cuti);
      $("#keterangan").val(decline_reason);
      $("#keterangan_decline").val(decline_reason);
    }
  </script>
@endsection