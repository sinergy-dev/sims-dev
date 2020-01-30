@extends('template.template_admin-lte')
@section('content')

  <section class="content-header">
    <h1>
      App Incident Management
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">DVG</li>
        <li class="active">App Incident Management</li>
    </ol>
  </section>

  <section class="content">

    @if(session('success'))
      <div class="alert-box success" id="alert"><span>notice: </span> {{ session('success') }}.</div>
    @endif

    <div class="box">
      <div class="box-header">
        <div class="pull-right">
          <button type="button" class="btn btn-primary pull-right float-right margin-left-custom" data-target="#modalAddIncident" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspAdd </button>
        </div>
        <!-- <div class="pull-left">
          <button type="button" class="btn btn-warning-eksport dropdown-toggle float-left  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download">&nbspExport</i>
          </button>
          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 13px; left: 10px; transform : translate3d(0px, 37px, 30px);">
            <a class="dropdown-item" href="{{action('INCIDENTController@downloadPDF')}}"> PDF </a><br>
            <a class="dropdown-item" href="{{action('INCIDENTController@exportExcelIM')}}"> EXCEL </a>
          </div>
        </div> -->
      </div>

      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped dataTable" id="datastable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Date</th>
                <th>Action Date</th>
                <th>User</th>
                <th>Module</th>
                <th>Case</th>
                <th>PIC</th>
                <th>Solution</th>
                <th>Request Via</th>
                <th>Status</th>
                <!-- <th>Action</th> -->
              </tr>
            </thead>
            <tbody>
              @foreach($datas as $data)
              <tr>
                <td>{{ $data->date }}</td>
                <td>{!!substr($data->updated_at,0,10)!!}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->modul }}</td>
                <td>{{ $data->kasus }}</td>
                <td>
                  @if($data->nik_pic == '118049912022')
                  Faiqoh
                  @elseif($data->nik_pic == '118040004048')
                  Ladinar Nanda 
                  @elseif($data->nik_pic == '118040005040')
                  Arkhab Maulana
                  @elseif($data->nik_pic == '118040006041')
                  Tito Maulana
                  @endif
                </td>
                <td>{{ $data->solution }}</td>
                <td>{{ $data->via }}</td>
                <td>
                  @if($data->status_problem == 'NEW')
                  <i style="opacity: 0.01">A</i><label class="status-initial">NEW</label>
                  @elseif($data->status_problem == 'DONE')
                    <i style="opacity: 0.01">C</i><label class="status-open btn-success" >DONE</label>
                  @endif
                </td>
                <!-- <td>
                @if(Auth::User()->id_territory == 'DVG')
                  @if($data->status_problem == 'NEW')
                    <button class="btn btn-xs btn-success" style="width: 70px; height: 30px;float: left" data-toggle="modal" data-target="#modal_submit" onclick="submit('{{$data->id_incident}}')">Submit</button>
                  @else
                    <button class="btn btn-xs btn-success" style="width: 70px; height: 30px;float: left" disabled>Submit</button>
                  @endif
                @else
                  @if($data->status_problem == 'NEW')
                  <button class="btn btn-xs btn-primary" style="width: 60px; height: 30px" data-target="#modalEdit" data-toggle="modal" onclick="update_incident('{{$data->modul}}','{{$data->id_incident}}','{{$data->kasus}}')">Edit</button>
                  @else
                  <button class="btn btn-xs btn-primary" disabled style="width: 60px; height: 30px">Edit</button>
                  @endif
                @endif
                </td> -->
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

    <!--MODAL ADD INCIDENT-->
  <div class="modal fade" id="modalAddIncident" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <center><h3 class="modal-title"><b>Add App Incident Management</b></h3></center>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_app_incident')}}" id="modalAddIncident" name="modalAddIncident">
            @csrf

            <div class="form-group">
              <label class="control-label">Date</label>
              <div >
                <input class="form-control" id="today" type="date" name="date" readonly="">
              </div>
            </div>

            <div class="form-group">
                <label>Request Via</label>
                <input type="text" class="form-control" name="via" id="via" required="">
            </div> 

            <div class="form-group">
              <label>User</label>
              <select type="text" class="form-control" placeholder="Select User" style="width: 100%" name="user" id="user" required>
                @foreach($users as $data)
                <!-- <option>Select User</option> -->
                <option value="{{$data->nik}}">{{$data->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
                <label>Module</label>
                <input class="form-control" name="modul" id="modul" required>
            </div> 

            <div class="form-group">
                <label>Case</label>
                <textarea class="form-control" name="kasus" id="kasus" required></textarea>
            </div>

            <div class="form-group">
                <label>Solution</label>
                <textarea class="form-control" name="add_solution" id="add_solution" required></textarea>
            </div>     
             
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
  
  <!--MODAL EDIT INCIDENT-->
  <div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Incident</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_app_incident')}}" id="modalEditIM" name="modalEditIM">
            @csrf

            <input type="text" name="id_incident_update" id="id_incident_update">

            <div class="form-group">
                <label>Modul</label>
                <input class="form-control" name="edit_modul" id="edit_modul">
            </div>

            <div class="form-group">
                <label>kasus</label>
                <input type="form-control" class="form-control" id="edit_kasus" name="edit_kasus">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success"><i class="fa fa-check"> </i>&nbspUpdate</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>  

  <div class="modal fade" id="modal_submit" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Submit</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_status_app_inc')}}" id="" name="">
            @csrf
          <input id="id_inc_submit" type="text" name="id_inc_submit" hidden>
          <div class="form-group">
            <label>Solution</label>
            <textarea class="form-control" id="solution" name="solution"></textarea>
            <br>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript">
     $('#datastable').DataTable( {
        "scrollX": true,
        scrollCollapse: true,
        "pageLength":25,
        "order": [[ 0, "desc" ]],
      });

    function submit(id_incident) {
      $('#id_inc_submit').val(id_incident);
    }

    $("#user").select2();


    // function update(id_incident, modul, kasus) {
    //   $('#id_incident_update').val(id_incident);
    //   $('#edit_modul').val(id_incident);
    //   $('#edit_kasus').val(id_incident);
      
    // }

    function update_incident(modul,id_incident,kasus){
      $('#edit_modul').val(modul);
      $('#id_incident_update').val(id_incident);
      $('#edit_kasus').val(kasus);
    }

    let today = new Date().toISOString().substr(0, 10);
    document.querySelector("#today").value = today;

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });
  </script>

  <style type="text/css">

div.table-responsive {
  overflow: auto;
  white-space: nowrap;
  }

/*div.table-responsive a {
  display: inline-block;
  color: white;
  text-align: center;
  padding: 10px;
  text-decoration: none;
}*/

/* width */
::-webkit-scrollbar {
  width: 20px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #888; 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background-color: #ffb523;
}
  </style>
@endsection