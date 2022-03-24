@extends('template.main')
@section('tittle')
Letter Number
@endsection
@section('head_css')
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.bootstrap.min.css">  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">

  <link rel="stylesheet" type="text/css" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <style type="text/css">
    .DTFC_LeftBodyLiner {
        overflow: hidden;
    }

    th {
      text-align: center;
    }

  /*  td {
      text-wrap: normal;
      word-wrap: break-word;
    }*/

    td>.truncate{
      /*word-wrap: break-word; */
      word-break:break-all;
      white-space: normal;
      width:200px;    
    }
    /*.data tr:nth-child(1){
      counter-reset: rowNumber;
      }
    .data tr {
          counter-increment: rowNumber;
      }
    .data tr td:first-child::before {
          content: counter(rowNumber);
          min-width: 1em;
          margin-left: 1.5em;
          text-align: center;
    }*/

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
    Daftar Buku Admin (Letter)
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Admin</li>
    <li class="active">Letter</li>
  </ol>
</section>
  
<section class="content">
  @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Letter Number :<h4> {{$pops->no_letter}}</h4></div>
      @endif

      @if (session('sukses'))
        <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Letter Number :<h4> {{$pops2->no_letter}}</h4></div>
      @endif

      @if (session('alert'))
    <div class="alert alert-success" id="alert">
        {{ session('alert') }}
    </div>
  @endif

  <div class="box">
    <div class="box-header with-border">

        <div class="pull-left">
          <!-- <label style="margin-top: 5px;margin-right: 5px">Filter Year</label> -->
        <!--   <select style="margin-right: 5px;width: 100px" class="form-control fa" id="year_filter">
              <option value="2020">&#xf073 &nbsp2020</option>
              <option value="2019">&#xf073 &nbsp2019</option>
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
          <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#modal_pr" data-toggle="modal" style="width: 100px; color: white"><i class="fa fa-plus"> </i>&nbsp Letter</button>
          @if($counts)
          <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#letter_backdate" data-toggle="modal" style="width: 100px; color: white; margin-right: 10px;"><i class="fa fa-plus"> </i>&nbsp Back Date</button>
          @else
          <button type="button" class="btn btn-success margin-bottom pull-right disabled" id="" data-target="#letter_backdate" data-toggle="modal" style="width: 100px; color: white; margin-right: 10px;" disabled><i class="fa fa-plus"> </i>&nbsp Back Date</button>
          @endif
          <!-- <a href="{{url('/downloadExcelLetter')}}"><button class="btn btn-warning" style=" margin-right: 10px;"><i class="fa fa-print"></i> EXCEL </button></a> -->
          <button class="btn btn-warning" onclick="exportLetter('{{action('LetterController@downloadExcel')}}')" style="margin-right: 10px;"><i class="fa fa-print"></i> Excel</button>
        </div>
    </div>
    <div class="box-body">
    <div class="row">
    	<div class="col-md-12">
	      <div class="nav-tabs-custom">
	        <ul class="nav nav-tabs" id="myTab">
	          @foreach($status_letter as $data)
                @if($data->status == 'A')
                    <li class="nav-item active">
                        <a class="nav-link active" id="{{ $data }}-tab" data-toggle="tab" href="#{{ $data->status }}" role="tab" aria-controls="{{ $data }}" aria-selected="true" onclick="changetabPane('{{$data->status}}')">All</a>
                @else
                    <li class="nav-item">
                        <a class="nav-link" id="{{ $data }}-tab" data-toggle="tab" href="#{{ $data->status }}" role="tab" aria-controls="{{ $data }}" aria-selected="true" onclick="changetabPane('{{$data->status}}')"> Backdate
                @endif
                        </a>
                    </li>
            @endforeach
	        </ul>
	        
	        <div class="tab-content">

	          <div class="tab-pane active">
	          	<div class="table-responsive DTFC_LeftBodyLiner">
	                <table class="table table-bordered nowrap display table-striped dataTable" id="data_all" width="100%" cellspacing="0">
	                  <thead>
	                    <tr>
	                      <th>No Letter</th>
	                      <th>Position</th>
	                      <th>Type</th>
	                      <th>Month</th>
	                      <th>Date</th>
	                      <th>To</th>
	                      <th><div class="truncate">Attention</div></th>
	                      <th><div class="truncate">Title</div></th>
	                      <th><div class="truncate">Project</div></th>
	                      <th><div class="truncate">Description</div></th>
	                      <th>From</th>
	                      <th>Division</th>
	                      <th>Project ID</th>
	                      <th>Note</th>
	                      <th>Action</th>
	                    </tr>
	                  </thead>
	                </table>
		          </div>
	          </div>

	        </div>
	      </div>
     	</div>
    </div>
    </div>
  </div>

  <!--MODAL ADD PROJECT-->
<div class="modal fade" id="modal_pr" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Letter</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_letter')}}" id="modal_pr" name="modal_pr">
            @csrf
          <div class="form-group">
            <label for="">Position</label>
            <select type="text" class="form-control" placeholder="Select Position" name="position" id="position" required>
                <option value="PMO">PMO</option>
                <option value="TEC">TEC</option>
                <option value="MSM">MSM</option>
                <option value="DIR">DIR</option>
                <option value="TAM">TAM</option>
                <option value="HRD">HRD</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Type of Letter</label>
            <select type="text" class="form-control" placeholder="Select Type of Letter" name="type" id="type" required>
                <option value="LTR">LTR (Surat Umum)</option>
                <option value="PKS">PKS (Perjanjian Kerja Sama)</option>
                <option value="BST">BST (Berita Acara Serah Terima)</option>
                <option value="ADM">ADM (Surat Administrasi & Backdate)</option>
                <option value="SGB">SBG (Surat Garansi Bank)</option>
                <option value="ADD">ADD (Addendum Perjanjian Kerja Sama)</option>
                <option value="AMD">AMD (Amandemen Perjanjian Kerja Sama)</option>
            </select>
          </div>
          <!-- <div class="form-group">
            <label for="">Date</label>
            <input type="date" class="form-control" name="date" id="date" required>
          </div> -->

          <div class="form-group">
            <label for="">Date</label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" class="form-control pull-right date" name="date" id="date_letter">
            </div>
          </div>
          <div class="form-group">
            <label for="">To</label>
            <input type="text" class="form-control" placeholder="Enter To" name="to" id="to" required>
          </div> 
          <div class="form-group">
            <label for="">Attention</label>
            <input type="text" class="form-control" placeholder="Enter Attention" name="attention" id="attention">
          </div> 
          <div class="form-group">
            <label for="">Title</label>
            <input type="text" class="form-control" placeholder="Enter Title" name="title" id="title">
          </div>
          <div class="form-group">
            <label for="">Project</label>
            <input type="text" class="form-control" placeholder="Enter Project" name="project" id="project">
          </div>
          <div class="form-group">
            <label for="">Description</label>
            <textarea class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
          </div>
          <div class="form-group">
            <label for="">Division</label>
            <select type="text" class="form-control" placeholder="Select Division" name="division" id="division" required>
                <option>PMO</option>
                <option>MSM</option>
                <option>Marketing</option>
                <option>HRD</option>
                <option>TEC</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Project ID</label>
            <input type="text" class="form-control" placeholder="Enter Project ID" name="project_id" id="project_id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
            <button type="submit" class="btn btn-primary" id="addLetter"><i class="fa fa-check"> </i>&nbspSubmit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>
<!-- BACKDATE -->
<div class="modal fade" id="letter_backdate" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Letter (Backdate)</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_letterbackdate')}}" id="letter_backdate" name="letter_backdate">
            @csrf
          <!-- <div class="form-group">
            <label>Backdate Number</label>
            <select type="text" class="form-control" placeholder="Select Backdate Number" style="width: 100%" name="backdate_num" id="backdate_num" required>
              @foreach($backdate_num as $data)
              <option value="{{$data->no_letter}}">{{$data->no_letter}}</option>
              @endforeach
            </select>
          </div> -->

          <div class="form-group">
            <label for="">Date</label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" class="form-control pull-right date" name="date" id="date_backdate" readonly>
            </div>
          </div>
          <div class="form-group">
            <label>Backdate Number</label>
            <select type="text" class="form-control" placeholder="Select Backdate Number" style="width: 100%" name="backdate_num" id="backdate_num" disabled>
              <!-- @foreach($backdate_num as $data)
              <option value="{{$data->no_letter}}">{{$data->no_letter}}</option>
              @endforeach -->
            </select>
          </div>
          <div class="form-group">
            <label for="">Position</label>
            <select type="text" class="form-control" placeholder="Select Position" name="position" id="position" required>
                <option value="PMO">PMO</option>
                <option value="TEC">TEC</option>
                <option value="MSM">MSM</option>
                <option value="DIR">DIR</option>
                <option value="TAM">TAM</option>
                <option value="HRD">HRD</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Type of Letter</label>
            <select type="text" class="form-control" placeholder="Select Type of Letter" name="type" id="type" required>
                <option value="LTR">LTR (Surat Umum)</option>
                <option value="PKS">PKS (Perjanjian Kerja Sama)</option>
                <option value="BST">BST (Berita Acara Serah Terima)</option>
                <option value="ADM">ADM (Surat Administrasi & Backdate)</option>
                <option value="SGB">SBG (Surat Garansi Bank)</option>
                <option value="ADD">ADD (Addendum Perjanjian Kerja Sama)</option>
                <option value="AMD">AMD (Amandemen Perjanjian Kerja Sama)</option>
            </select>
          </div>
          <!-- <div class="form-group">
            <label for="">Date</label>
            <input type="date" class="form-control" name="date" id="date" required>
          </div> -->
          <div class="form-group">
            <label for="">To</label>
            <input type="text" class="form-control" placeholder="Enter To" name="to" id="to" required>
          </div> 
          <div class="form-group">
            <label for="">Attention</label>
            <input type="text" class="form-control" placeholder="Enter Attention" name="attention" id="attention">
          </div> 
          <div class="form-group">
            <label for="">Title</label>
            <input type="text" class="form-control" placeholder="Enter Title" name="title" id="title">
          </div>
          <div class="form-group">
            <label for="">Project</label>
            <input type="text" class="form-control" placeholder="Enter Project" name="project" id="project">
          </div>
          <div class="form-group">
            <label for="">Description</label>
            <textarea class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
          </div>
          <!-- <div class="form-group">
            <label for="">From</label>
            <input type="text" class="form-control" id="from" name="from" placeholder="Enter From" required>
          </div> -->
          <div class="form-group">
            <label for="">Division</label>
            <select type="text" class="form-control" placeholder="Select Division" name="division" id="division" required>
                <option>PMO</option>
                <option>MSM</option>
                <option>Marketing</option>
                <option>HRD</option>
                <option>TEC</option>
            </select>
          </div>
          <div class="form-group">
            <label for="">Project ID</label>
            <input type="text" class="form-control" placeholder="Enter Project ID" name="project_id" id="project_id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
            <button type="submit" class="btn btn-primary" id="addBackdate"><i class="fa fa-check"> </i>&nbspSubmit</button>
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
          <h4 class="modal-title">Edit Letter</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_letter')}}" id="modaledit" name="modaledit">
            @csrf
          <input type="text" placeholder="Enter No Letter" name="edit_no_letter" id="edit_no_letter" hidden>
          <div class="form-group">
            <label for="">To</label>
            <input type="text" class="form-control" placeholder="Enter To" name="edit_to" id="edit_to">
          </div>
          <div class="form-group">
            <label for="">Attention</label>
            <input type="text" class="form-control" placeholder="Enter Attention" name="edit_attention" id="edit_attention">
          </div> 
          <div class="form-group">
            <label for="">Title</label>
            <input type="text" class="form-control" placeholder="Enter Title" name="edit_title" id="edit_title">
          </div> 
          <div class="form-group">
            <label for="">Project</label>
            <input type="text" class="form-control" placeholder="Enter Project" name="edit_project" id="edit_project">
          </div>
          <div class="form-group">
            <label for="">Description</label>
            <textarea type="text" class="form-control" placeholder="Enter Description" name="edit_description" id="edit_description"> </textarea>
          </div>
          <div class="form-group">
            <label for="">Project ID</label>
            <input type="text" class="form-control" placeholder="Enter Project ID" name="edit_project_id" id="edit_project_id">
          </div>
          <div class="form-group">
            <label for="">Note</label>
            <input type="text" class="form-control" placeholder="Enter Note" name="edit_note" id="edit_note">
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

<div class="modal fade" id="tunggu" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-body">
          <div class="form-group">
            <div class="">Sedang diproses. . .</div>
          </div>
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
  <script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
  <!-- <script type="text/javascript" src="{{asset('js/dataTables.fixedColumns.min.js')}}"></script> -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
@endsection

@section('script')
  <script type="text/javascript">
  	$(document).ready(function(){
        $("#addBackdate").prop("disabled",true)  
  	})

    $('#date_letter').datepicker({
      autoclose: true,
    }).attr('readonly','readonly').css('background-color','#fff');
    $('#addLetter').click(function(){
      $('#tunggu').modal('show')
      $('#modal_pr').modal('hide')
      setTimeout(function() {$('#tunggu').modal('hide');}, 2000);
    });

    $('#addBackdate').click(function(){
      $('#tunggu').modal('show')
      $('#letter_backdate').modal('hide')
      setTimeout(function() {$('#tunggu').modal('hide');}, 2000);
    });

    $('#date_backdate').datepicker({
      autoclose: true,
    }).on('hide', function(e) {
        console.log($("#date_backdate").val());
        // $("#backdate_num").val("").trigger('change')
        $('#backdate_num').empty().trigger("change");
        $.ajax({
            type:"GET",
            url:"get_backdate_letter",
            data:{
              tanggal:$('#date_backdate').val(),
            },
            success:function(result){
              console.log(result.results.length)
              if (result.results.length == 0) {
                $('#submitBd').prop("disabled",true)  
                $("#backdate_num").prop("disabled",true)    
                $("#addBackdate").prop("disabled",true)        
              }else{
                $('#submitBd').prop("disabled",false)
                $("#backdate_num").prop("disabled",false)            
                $("#backdate_num").select2({
                  data: result.results
                })         
                $("#addBackdate").prop("disabled",false)       
              }          
            }
          })
    });

    function edit_letter(no_letter,to,attention,title,project,description,project_id,note) {
      $('#modaledit').modal('show');
      $('#edit_no_letter').val(no_letter);
      // $('#edit_to').val(to);
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

      if (project_id == "null") {
        '';
      } else {
        $('#edit_project_id').val(project_id);
      }

      if (note == "null") {
        '';
      } else {
        $('#edit_note').val(note);
      }
    }

    initLetterTable();

    function initLetterTable() {
      $("#data_all").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('getdataletter')}}",
          "dataSrc": function (json){

            json.data.forEach(function(data,index){
              if("{{Auth::User()->nik}}" == data.nik) {
                var x = '"' + data.no_letter + '","' + data.to + '","' + data.attention+ '","' +data.title+ '","' +data.project+ '","' +data.description+ '","' +data.project_id+ '","' +data.note+ '"'
                data.btn_edit = "<button class='btn btn-sm btn-primary' onclick='edit_letter(" + x + ")'>&nbsp Edit</button>";
              } else {
                data.btn_edit = "<button class='btn btn-sm btn-primary disabled'>&nbsp Edit</button>";
              }
                
            });
            return json.data;
            
          }
        },
        "columns": [
          { "data": "no_letter","width": "20%"},
          { "data": "position","width": "20%"},
          { "data": "type_of_letter","width": "20%"},
          { "data": "month","width": "20%"},
          { "data": "date","width": "20%"},
          { "data": "to","width": "20%"},
          {
             "render": function ( data, type, row, meta ) {
                if(row.attention == null){
                  return '<div class="truncate"> - </div>'
                } else {
                  return '<div class="truncate">' + row.attention + '</div>'
                }
              }
          },
          {
             "render": function ( data, type, row, meta ) {
                if (row.title == null) {
                  return '<div class="truncate"> - </div>'
                } else {
                  return '<div class="truncate">' + row.title + '</div>'                  
                }
              }
          },
          {
             "render": function ( data, type, row, meta ) {
                if (row.project == null) {
                  return '<div class="truncate"> - </div>'
                } else {
                  return '<div class="truncate">' + row.project + '</div>'                  
                }
              }
          },
          {
             "render": function ( data, type, row, meta ) {
                if (row.description == null) {
                  return '<div class="truncate"> - </div>'
                } else {
                  return '<div class="truncate">' + row.description + '</div>'                  
                }
              }
          },
          { "data": "name","width": "20%"},
          { "data": "division","width": "20%"},
          { "data": "project_id","width": "20%"},
          { "data": "note","width": "20%"},
          {
            "className": 'btn_edit',
            "orderable": false,
            "data": "btn_edit",
            "defaultContent": ''
          },

        ],
        // "columnDefs": [
        //   { "width": "20%", "targets": 7 },
        // ],
        "searching": true,
        // "lengthChange": false,
        "info":false,
        "scrollX": true,
        "order": [[ 0, "desc" ]],
        "fixedColumns":   {
            leftColumns: 1
        },
        "responsive":true,
        "orderCellsTop": true,
        "pageLength": 20,
      })
    }

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $("#backdate_num").select2();

    function changetabPane(status) {
      $('#data_all').DataTable().ajax.url("{{url('getfilteryearletter')}}?status=" + status + "&year=" + $('#year_filter').val()).load();
    }

    $("#year_filter").change(function(){
      $('#data_all').DataTable().ajax.url("{{url('getfilteryearletter')}}?status=A&year=" + this.value).load();
    });

    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $($.fn.dataTable.tables( true ) ).css('width', '100%');
        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    }); 

    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    }); 

    $('#myTab a').click(function(e) {
      e.preventDefault();
      $(this).tab('show');
    });

    // store the currently selected tab in the hash value
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
      var id = $(e.target).attr("href").substr(1);
      window.location.hash = id;
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#myTab a[href="' + hash + '"]').tab('show');

    function exportLetter(url){
      window.location = url + "?year=" + $("#year_filter").val();
    }


  </script>
@endsection