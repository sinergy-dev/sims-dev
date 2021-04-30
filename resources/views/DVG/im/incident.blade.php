@extends('template.main')
@section('head_css')
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
@endsection
@section('content')

  <section class="content-header">
    <h1>
      Incident Management
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">DVG</li>
        <li class="active">Incident Management</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header">
        <div class="pull-right">
          <button type="button" class="btn btn-primary pull-right float-right margin-left-custom" data-target="#modalAddIncident" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspAdd IM </button>
        </div>
        <div class="pull-left">
          <button type="button" class="btn btn-warning-eksport dropdown-toggle float-left  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download">&nbspExport</i>
          </button>
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 13px; left: 10px; transform : translate3d(0px, 37px, 30px);">
              <a class="dropdown-item" href="{{action('INCIDENTController@downloadPDF')}}"> PDF </a><br>
              <a class="dropdown-item" href="{{action('INCIDENTController@exportExcelIM')}}"> EXCEL </a>
            </div>
        </div>
      </div>

      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped dataTable" id="datastable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <th>Date</th>
                <th>Case</th>
                <th>User</th>
                <th>Division</th>
                <th>Status</th>
                <th>Solution</th>
                <th>Time</th>
                <th>Impact</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($datas as $data)
              <tr>
                <td>{{ $data->no }}</td>
                <td>{{ $data->date }}</td>
                <td>{{ $data->chase }}</td>
                <td>{{ $data->user }}</td>
                <td>{{ $data->division }}</td>
                <td>{{ $data->status }}</td>
                <td>{{ $data->solution }}</td>
                <td>{{ $data->time }}</td>
                <td>{{ $data->impact }}</td>
                <td>

                <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modalEdit" data-toggle="modal" style="width: 40px;height: 40px;text-align: center;" onclick="incident('{{$data->no}}','{{$data->date}}','{{$data->user}}','{{$data->chase}}','{{$data->division}}','{{$data->status}}','{{$data->solution}}','{{$data->time}}','{{$data->impact}}')"></button>

                <a href="{{ url('delete_incident', $data->no) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data?')">
                    </button></a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

    <!--MODAL-->
    <!--MODAL ADD INCIDENT-->
    <div class="modal fade" id="modalAddIncident" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <center><h3 class="modal-title"><b>Add Incident Management</b></h3></center>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_incident')}}" id="modalAddIncident" name="modalAddIncident">
            @csrf
            
            <!-- <div class="form-group">
                <label>No</label>
                <input class="form-control" id="no" name="no" required>
            </div> -->

            <div class="form-group">
                <label>Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>

            <div class="form-group">
                <label>Case</label>
                <input type="form-control" class="form-control" id="chase" name="chase" required>
            </div>

            <div class="form-group">
                <label>User</label>
                <input class="form-control" id="user" name="user" required>
            </div>

            <div class="form-group">
                <label>Division</label>
                <select class="form-control" id="division" name="division" required>
                      <option value="DPG">DPG</option>
                      <option value="DVG">DVG</option>
                  </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select class="form-control" id="status" name="status" required>
                      <option value="Pending">Pending</option>
                      <option value="Done">Done</option>
                      <option value="Reject">Reject</option>
                  </select>
            </div>

            <div class="form-group">
                <label>Solution</label>
                <input class="form-control" id="solution" name="solution" required>
            </div>

            <div class="form-group">
                <label>Time</label>
                <input class="form-control" id="time" name="time" required>
            </div>

            <div class="form-group">
                <label>Impact</label>
                <input class="form-control" id="impact" name="impact" required>
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
          <form method="POST" action="{{url('/update_incident')}}" id="modalEditIM" name="modalEditIM">
            @csrf

            <div class="form-group">
              <label>No</label>
              <input class="form-control" id="no" name="edit_no">
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="date" class="form-control" id="edit_date" name="edit_date" required>
            </div>

            <div class="form-group">
                <label>Case</label>
                <input type="form-control" class="form-control" id="edit_chase" name="edit_chase" required>
            </div>

            <div class="form-group">
                <label>User</label>
                <input class="form-control" id="edit_user" name="edit_user" required>
            </div>

            <div class="form-group">
                <label>Division</label>
                <select class="form-control" id="edit_division" name="edit_division" required>
                      <option value="DPG">DPG</option>
                      <option value="DVG">DVG</option>
                  </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select class="form-control" id="edit_status" name="edit_status" required>
                      <option value="Pending">Pending</option>
                      <option value="Done">Done</option>
                      <option value="Reject">Reject</option>
                  </select>
            </div>

            <div class="form-group">
                <label>Solution</label>
                <input class="form-control" id="edit_solution" name="edit_solution" required>
            </div>

            <div class="form-group">
                <label>Time</label>
                <input class="form-control" id="edit_time" name="edit_time" required>
            </div>

            <div class="form-group">
                <label>Impact</label>
                <input class="form-control" id="edit_impact" name="edit_impact" required>
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
</section>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript">
     $('#datastable').DataTable( {
        "scrollX": true
        } );

     function incident(no,date,user,chase,id_division,status,solution,time,impact) {
      $('#no').val(no);
      $('#edit_date').val(date);
      $('#edit_user').val(user);
      $('#edit_chase').val(chase);
      $('#edit_division').val(id_division);
      $('#edit_status').val(status);
      $('#edit_solution').val(solution);
      $('#edit_time').val(time);
      $('#edit_impact').val(impact);
    }
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