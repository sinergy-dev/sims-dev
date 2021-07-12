@extends('template.main')
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <link rel="stylesheet" type="text/css" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

  <style type="text/css">
    .transparant{
      background-color: Transparent;
      background-repeat:no-repeat;
      border: none;
      cursor:pointer;
      overflow: hidden;
      outline:none;
      width: 25px;
    }

    .btnPR{
      color: #fff;
      background-color: #007bff;
      border-color: #007bff;
      width: 170px;
      padding-top: 4px;
      padding-left: 10px;
    }

    .select2{
      width: 100%!important;
    }
</style>
@endsection
@section('content')

<section class="content-header">
  <h1>
    Daftar Buku Admin (DO)
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Admin</li>
    <li class="active">Delivery Order</li>
  </ol>
</section>


<section class="content">
  @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
  @endif

  @if (session('success'))
    <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Number DO :<h4> {{$pops->no_do}}</h4></div>
  @endif

  @if (session('alert'))
    <div class="alert alert-success" id="alert">
        {{ session('alert') }}
    </div>
  @endif

  <div class="box">
    <div class="box-header with-border">
      <div class="pull-left">
        <!-- <label style="margin-top: 5px;margin-right: 5px">Filter Year</label>
        <select style="margin-right: 5px;width: 100px" class="form-control fa" id="year_filter">
            <option value="2021">&#xf073 &nbsp2021</option>
        </select> -->

        <select style="margin-right: 5px;width: 100px" class="form-control btn-primary btn-flat fa" id="year_filter">
            <option value="{{$tahun}}">&#xf073 &nbsp{{$tahun}}</option>
            @foreach($year_before as $years)
              @if($years->year != $tahun)
                <option value="{{$years->year}}">&#xf073 &nbsp{{$years->year}}</option>
              @endif
            @endforeach
        </select>
      </div>
      <div class="pull-right">
          <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#modal_add" data-toggle="modal" style="width: 200px; height: 40px; color: white"><i class="fa fa-plus"> </i>&nbsp Number Delivery Order</button>
          <a id="btn_download" href="#"><button class="btn btn-warning" style="height: 40px; margin-right: 10px;"><i class="fa fa-print"></i> EXCEL </button></a>
      </div>
    </div>


    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped dataTable display nowrap" id="data_Table" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <th>Type of Letter</th>
                <th>Month</th>
                <th>Date</th>
                <th>To</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Attention</th>
                <th>Subject</th>
                <th>From</th>
                <th>Project ID</th>
                <th>PO Number</th>
                <th>Note</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="products-list" name="products-list">
            </tbody>
            <tfoot>
            </tfoot>
        </table>
      </div>
    </div>
  </div>

  <!--MODAL ADD PROJECT-->
  <div class="modal fade" id="modal_add" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Add Number Delivery Order</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('/store_do')}}" id="modal_pr" name="modal_pr">
              @csrf
            <div class="form-group">
              <label for="">Date</label>
              <input type="date" class="form-control" name="date" id="date" required>
            </div>
            <div class="form-group">
              <label for="">To</label>
              <input type="text" class="form-control" placeholder="To" name="to" id="to" required>
            </div> 
            <div class="form-group">
              <label for="">Address</label>
              <textarea type="text" class="form-control" placeholder="Enter Address" name="address" id="address" ></textarea>
            </div>
            <div class="form-group">
              <label for="">Phone Number</label>
              <input type="number" class="form-control" placeholder="Enter Phone Number" name="no_telp" id="no_telp" >
            </div>
            <div class="form-group">
              <label for="">Attention</label>
              <input type="text" class="form-control" placeholder="Enter Attention" name="attention" id="attention" >
            </div> 
            <div class="form-group">
              <label for="">Subject</label>
              <textarea class="form-control" id="subject" name="subject" placeholder="Enter Subject"></textarea>
            </div>
            <div class="form-group">
              <label>From</label>
              <input type="text" class="form-control" name="from" value="PT. Sinergy Informasi Pratama" id="from">
            </div>
            <div class="form-group">
              <label for="">Project ID</label>
              <select type="text" class="form-control select2" name="project_id" id="project_id" required>
               <option>-- Select Id Project --</option>
                @foreach($id_project as $data)
                <option value="{{$data->id_project}}">{{$data->id_project}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="">PO Number</label>
              <select type="text" class="form-control select2" name="no_po" id="no_po">
               <option value="-">-- Select PO Number --</option>
                @foreach($no_po as $data)
                <option value="{{$data->no_po}}">{{$data->no_po}}</option>
                @endforeach
              </select>
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


  <!--Modal Edit-->
  <div class="modal fade" id="modaledit" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Edit Delivery Order</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('/update_do')}}" id="modaledit" name="modaledit">
              @csrf
            <input type="text" placeholder="Enter No PR" name="edit_no_do" id="edit_no_do" hidden>
            <div class="form-group">
              <label for="">To</label>
              <input type="text" class="form-control" name="edit_to" id="edit_to" >
            </div>
            <div class="form-group">
              <label for="">Attention</label>
              <input type="text" class="form-control" name="edit_attention" id="edit_attention" >
            </div> 
            <div class="form-group">
              <label for="">Address</label>
              <textarea type="text" class="form-control" name="edit_address" id="edit_address" ></textarea>
            </div> 
            <div class="form-group">
              <label for="">Phone Number</label>
              <input type="text" class="form-control" name="edit_no_telp" id="edit_no_telp" >
            </div>
            <div class="form-group">
              <label for="">Subject</label>
              <textarea type="text" class="form-control" name="edit_subject" id="edit_subject" > </textarea>
            </div>
            <div class="form-group">
              <label for="">Project ID</label>
              <select type="text" class="form-control select2" name="edit_project_id" id="edit_project_id" >
               <option>-- Select Id Project --</option>
                @foreach($id_project as $data)
                <option value="{{$data->id_project}}">{{$data->id_project}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="">PO Number</label>
              <select type="text" class="form-control select2" name="edit_no_po" id="edit_no_po">
               <option value="-">-- Select PO Number --</option>
                @foreach($no_po as $data)
                <option value="{{$data->no_po}}">{{$data->no_po}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="">Note</label>
              <input type="text" class="form-control" name="edit_note" id="edit_note">
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

</section>

@endsection

@section('scriptImport')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/dataTables.fixedColumns.min.js')}}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
@endsection

@section('script')
  <script type="text/javascript">
    $('#project_id').select2({
      dropdownParent: $('#modal_add')
    });
    $('#no_po').select2({
      dropdownParent: $('#modal_add')
    });
    $('#edit_project_id').select2({
      dropdownParent: $('#modaledit')
    });
    $('#edit_no_po').select2({
      dropdownParent: $('#modaledit')
    });
    function edit_do(no,to,attention,address,no_telp,subject,project_id,no_po,note) {
      $('#modaledit').modal('show');
      $('#edit_no_do').val(no);
      $('#edit_to').val(to);

      if (attention == "null") {
        '';
      } else {
        $('#edit_attention').val(attention);
      }

      if (address == "null") {
        '';
      } else {
        $('#edit_address').val(address);
      }

      if (no_telp == "null") {
        '';
      } else {
        $('#edit_no_telp').val(no_telp);
      }

      if (subject == "null") {
        '';
      } else {
        $('#edit_subject').val(subject);
      }

      if (project_id == "null") {
        'Select Id Project';
      } else {
        // $('#edit_project_id').val(project_id);
        $('#edit_project_id').val(project_id);
        $('#edit_project_id').select2().trigger('change');
      }

      if (no_po == "null") {
        'Select PO Number';
      } else {
        $('#edit_no_po').val(no_po);
        $('#edit_no_po').select2().trigger('change');
      }

      if (note == "null") {
        '';
      } else {
        $('#edit_note').val(note);
      }

    }

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    initDOTable();

    $("#year_filter").change(function(){
      $('#data_Table').DataTable().ajax.url("{{url('getfilteryeardo')}}?data=" + this.value).load();
    });

    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    }); 

    function initDOTable() {
      $("#data_Table").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('getdatado')}}",
          "dataSrc": function (json){

            json.data.forEach(function(data,index){
                var x = '"' + data.no + '","' + data.to + '","' + data.attention+ '","' +data.address+ '","' +data.no_telp+ '","' +data.subject+ '","' +data.project_id+ '","' +data.no_po+ '","' +data.note+ '"'
                data.btn_edit = "<button class='btn btn-xs btn-primary' onclick='edit_do(" + x + ")'>&nbsp Edit</button>";
                
            });
            return json.data;
            
          }
        },
        "columns": [
          { "data": "no_do" },
          { "data": "type_of_letter" },
          { "data": "month" },
          { "data": "date" },
          { "data": "to" },
          { "data": "address"},
          { "data": "no_telp" },
          { "data": "attention" },
          { "data": "subject" },
          { "data": "from" },
          { "data": "project_id" },
          { "data": "no_po" },
          { "data": "note" },
          {
            "className": 'btn_edit',
            "orderable": false,
            "data": "btn_edit",
            "defaultContent": ''
          },
        ],
        "searching": true,
        // "lengthChange": false,
        "info":false,
        "scrollX": true,
        "order": [[ 0, "desc" ]],
        "fixedColumns":   {
            leftColumns: 1
        },
        "pageLength": 20,
        "responsive":true,
        "orderCellsTop": true,
      })
    }

    $(document).ready(function() {
      $('#btn_download').on('click', function() {
        $('#btn_download').attr("href","{{url('/downloadExcelDO')}}?year=" + $('#year_filter').val())
      })
    });
  </script>
@endsection