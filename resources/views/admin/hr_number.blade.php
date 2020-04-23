@extends('template.template_admin-lte')
@section('content')
  <style type="text/css">
    .DTFC_LeftBodyLiner {
      overflow: hidden;
    }
  </style>

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

        <div class="pull-right">
          <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#modal_pr" data-toggle="modal" style="width: 150px; height: 40px; color: white"><i class="fa fa-plus"> </i>&nbsp Penomoran HR</button>
          <a href="{{url('/downloadExcelAdminHR')}}"><button class="btn btn-warning" style="height: 40px; margin-right: 10px;"> EXCEL </button></a>
        </div>
      </div>

      <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered nowrap table-striped dataTable" id="data_Table" width="100%" cellspacing="0">
              <thead>
                <tr>
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
                  <!-- <th>Project ID</th> -->
                  <!-- <th>Note</th> -->
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
          <!-- <div class="form-group">
            <label for="">Position</label>
            <select type="text" class="form-control" placeholder="Select Position" name="position" id="position" required>
                <option>PMO</option>
                <option>PRE</option>
                <option>MSM</option>
                <option>SAL</option>
                <option>FA</option>
                <option>HR</option>
            </select>
          </div> -->
          <div class="form-group">
            <label for="">Type of Letter</label>
            <select type="text" class="form-control" placeholder="Select Type of Letter" name="type" id="type" required>
                <option value="PKWT">PKWT</option>
                <option value="SK">SK</option>
                <option value="SP">SP</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">PT</label>
            <select type="text" class="form-control" placeholder="Select PT" name="pt" id="pt" required>
                <option>SIP</option>
                <option>MSP</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Date</label>
            <input type="date" class="form-control" name="date" id="date" required>
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
                <option>TECHNICAL</option>
                <option>MSM</option>
                <option>SAL</option>
                <option>FA</option>
                <option>HR</option>
                <option>AM</option>
            </select>
          </div>
          <!-- <div class="form-group">
            <label for="">Issuance</label>
            <input type="text" class="form-control" placeholder="Enter Issuance" name="issuance" id="issuance">
          </div> -->
          <div class="form-group">
            <label for="">Project ID</label>
            <input type="text" class="form-control" placeholder="Project ID" name="project_id" id="project_id">
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
          <h4 class="modal-title">Edit </h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_admin_hr')}}" id="modaledit" name="modaledit">
            @csrf
          <input type="text" placeholder="Enter No PR" name="edit_no_letter" id="edit_no_letter">
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
</style>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/dataTables.fixedColumns.min.js')}}"></script>
  <script type="text/javascript">

    function edit_hr_number(no,to,attention,title,project,description,from) {
      $('#modaledit').modal('show');
      $('#edit_no_letter').val(no);
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
                var x = '"' + data.no + '","' + data.to + '","' + data.attention+ '","' +data.title+ '","' +data.project+ '","' +data.description+ '"'
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
          { "data": "name" },
          { "data": "description" },
          { "data": "name" },
          {
            "className": 'btn_edit',
            "orderable": false,
            "data": "btn_edit",
            "defaultContent": ''
          },
        ],
        "searching": true,
        "lengthChange": false,
        "info":false,
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

    /*$('#data_Table').DataTable( {
      "scrollX": true,
      "order": [[ 0, "desc" ]],
      fixedColumns:   {
        leftColumns: 1,
      },
      pageLength: 20,
    });*/

    $(".dismisbar").click(function(){
         $(".notification-bar").slideUp(300);
        }); 
  </script>
@endsection