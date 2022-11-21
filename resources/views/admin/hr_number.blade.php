@extends('template.main')
@section('tittle')
HR Number
@endsection
@section('head_css')
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <style type="text/css">
    .DTFC_LeftBodyLiner {
      overflow: hidden;
    }
    th {
      text-align: center;
    }
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
  </style>
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Daftar Buku Admin (HR)
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Admin</li>
      <li class="active">HR</li>
    </ol>
  </section>

  <section class="content">
    @if (session('update'))
      <div class="alert alert-warning" id="alert">
          {{ session('update') }}
      </div>
    @endif

        @if (session('success'))
          <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Number HR :<h4> {{$pops->no_letter}}</h4></div>
        @endif

        @if (session('alert'))
          <div class="alert alert-success" id="alert">
            {{ session('alert') }}
          </div>
        @endif

    <div class="box">
      <div class="box-header with-border">

        <div class="pull-left">
          <select style="margin-right: 5px;width: 100px" class="form-control btn-primary" id="year_filter">
            <option value="{{$year}}">&nbsp{{$year}}</option>
            @foreach($year_before as $years)
              @if($years->year != $year)
                <option value="{{$years->year}}">&nbsp{{$years->year}}</option>
              @endif
            @endforeach
          </select>
        </div>

        <div class="pull-right">
          <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#modal_pr" data-toggle="modal" style="width: 120px;color: white"><i class="fa fa-plus"> </i>&nbsp Number HR</button>
          <button class="btn btn-warning" onclick="exportHrNumber('{{action('HRNumberController@downloadExcelAdminHR')}}')" style="margin-right: 10px;"><i class="fa fa-print"></i>Excel</button>
        </div>
      </div>

      <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered nowrap table-striped dataTable" id="data_Table" width="100%" cellspacing="0">
              <thead>
                <tr style="text-align: center;">
                  <th>No</th>
                  <th>Type</th>
                  <th>Division</th>
                  <th>PT</th>
                  <th>Month</th>
                  <th>Date</th>
                  <th>To</th>
                  <th>Attention</th>
                  <th>Title</th>
                  <th>Project</th>
                  <th>Description</th>
                  <th>From</th>
                  <th>Division</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
      </div>
    </div>

    <!--MODAL ADD PROJECT-->
<div class="modal fade" id="modal_pr" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Number HR</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_admin_hr')}}" id="modal_pr" name="modal_pr">
            @csrf
          <div class="form-group">
            <label for="">Type of Letter</label>
            <select type="text" class="form-control" placeholder="Select Type of Letter" name="type" id="type" required>
                <option value="PKWT">PKWT</option>
                <option value="PKWTT">PKWTT</option>
                <option value="SK">SK</option>
                <option value="SP">SP</option>
                <option value="IDS">IDS</option>
                <option value="Legal">Legal</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Company</label>
            <select type="text" class="form-control" placeholder="Select PT" name="pt" id="pt" required>
                <option>SIP</option>
                <option>MSP</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Date</label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" class="form-control pull-right date" name="date" id="date_hr">
            </div>
          </div>
          <div class="form-group">
            <label for="">To</label>
            <input type="text" class="form-control" placeholder="To" name="to" id="to" required>
          </div> 
          <div class="form-group">
            <label for="">Attention</label>
            <input type="text" class="form-control" placeholder="Enter Attention" name="attention" id="attention" >
          </div> 
          <div class="form-group">
            <label for="">Title</label>
            <input type="text" class="form-control" placeholder="Enter Title" name="title" id="title" >
          </div>
          <div class="form-group">
            <label for="">Project</label>
            <input type="text" class="form-control" placeholder="Enter Project" name="project" id="project" >
          </div>
          <div class="form-group">
            <label for="">Description</label>
            <textarea class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
          </div>
          <div class="form-group">
            <label for="">Division</label>
            <select type="text" class="form-control" placeholder="Select Division" name="division" id="division" required>
                <option>PMO</option>
                <option>SOL</option>
                <option>SID</option>
                <option>BCD</option>
                <option>MSM</option>
                <option>SAL</option>
                <option>FA</option>
                <option>HR</option>
                <option>AM</option>
            </select>
          </div>
          <!-- <div class="form-group">
            <label for="">Project ID</label>
            <input type="text" class="form-control" placeholder="Project ID" name="project_id" id="project_id">
          </div> -->
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
          <h4 class="modal-title">Edit </h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_admin_hr')}}" id="modaledit" name="modaledit">
            @csrf
          <input type="text" hidden placeholder="Enter No PR" name="edit_no_letter" id="edit_no_letter">
          <div class="form-group">
            <label for="">Type of Letter</label>
            <select type="text" class="form-control" placeholder="Select Type of Letter" name="edit_type" id="edit_type" required>
                <option value="PKWT">PKWT</option>
                <option value="PKWTT">PKWTT</option>
                <option value="SK">SK</option>
                <option value="SP">SP</option>
                <option value="IDS">IDS</option>
                <option value="Legal">Legal</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Company</label>
            <select type="text" class="form-control" placeholder="Select PT" name="edit_company" id="edit_company" required>
                <option>SIP</option>
                <option>MSP</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Date</label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="date" class="form-control pull-right date" name="edit_date" id="edit_date">
            </div>
          </div>
          <div class="form-group">
            <label for="">To</label>
            <input type="text" class="form-control" placeholder="Enter To" name="edit_to" id="edit_to" >
          </div>
          <div class="form-group">
            <label for="">Attention</label>
            <input type="text" class="form-control" placeholder="Enter Attention" name="edit_attention" id="edit_attention" >
          </div> 
          <div class="form-group">
            <label for="">Title</label>
            <input type="text" class="form-control" placeholder="Enter Title" name="edit_title" id="edit_title" >
          </div> 
          <div class="form-group">
            <label for="">Project</label>
            <input type="text" class="form-control" placeholder="Enter Project" name="edit_project" id="edit_project" >
          </div>
          <div class="form-group">
            <label for="">Description</label>
            <textarea type="text" class="form-control" placeholder="Enter Description" name="edit_description" id="edit_description" > </textarea>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
@endsection

@section('script')
  <script type="text/javascript">

    $('#date_hr').datepicker({
      autoclose: true,
    }).attr('readonly','readonly').css('background-color','#fff');

    function edit_hr_number(no,to,attention,title,project,description,type,company,date) {
      $('#modaledit').modal('show');
      $('#edit_no_letter').val(no);
      $('#edit_type').val(type);
      $('#edit_company').val(company);
      $('#edit_date').val(date);

      if (to == "null") {
        '';
      } else {
        $('#edit_to').val(to);
      }
      if (attention == "null") {
        '';
      } else {
        $('#edit_attention').val(attention);
      }
      if (title == "null") {
        '';
      } else {
        $('#edit_title').val(title);
      }
      if (project == "null") {
        '';
      } else {
        $('#edit_project').val(project);
      }

      if (description == "null") {
        '';
      } else {
        $('#edit_description').val(description);
      }
    }

    initHRTable();

    function initHRTable() {
       $("#data_Table").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('getdatahrnumber')}}",
          "dataSrc": function (json){

            json.data.forEach(function(data,index){
              if("{{Auth::User()->nik}}" == data.from) {
                var x = '"' + data.no + '","' + data.to + '","' + data.attention+ '","' +data.title+ '","' +data.project+ '","' +data.description+ '","' +data.type_of_letter+ '","' +data.pt+ '","' +data.date+ '"'
                data.btn_edit = "<button class='btn btn-xs btn-primary' onclick='edit_hr_number(" + x + ")'>&nbsp Edit</button>";
              } else {
                data.btn_edit = "<button class='btn btn-xs btn-primary disabled'>&nbsp Edit</button>";
              }
                
            });
            return json.data;
            
          }
        },
        "columns": [
          { "data": "no_letter" },
          { "data": "type_of_letter" },
          { "data": "divsion" },
          { "data": "pt" },
          { "data": "month" },
          { "data": "date" },
          { "data": "to"},
          { "data": "attention" },
          { "data": "title" },
          { "data": "project" },
          { "data": "description" },
          { "data": "name" },
          { "data": "division" },
          {
            "className": 'btn_edit',
            "orderable": false,
            "data": "btn_edit",
            "defaultContent": ''
          },
        ],
        "searching": true,
        "scrollX": true,
        "order": [[ 0, "desc" ]],
        "fixedColumns":   {
            leftColumns: 1
        },
        "pageLength": 20,
      })
    }

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $("#year_filter").change(function(){
      $('#data_Table').DataTable().ajax.url("{{url('getfilteryearhrnumber')}}?data=" + this.value).load();
    });

    function exportHrNumber(url){
      window.location = url + "?year=" + $("#year_filter").val();
    }

    $(".dismisbar").click(function(){
     $(".notification-bar").slideUp(300);
    }); 
  </script>
@endsection