@extends('template.template_admin-lte')
@section('content')
<style type="text/css">
	.DTFC_LeftBodyLiner {
  		overflow: hidden;
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
</style>
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
          <label style="margin-top: 5px;margin-right: 5px">Filter Year</label>
          <select style="margin-right: 5px;width: 100px" class="form-control fa" id="year_filter">
              <option value="2020">&#xf073 &nbsp2020</option>
              <option value="2019">&#xf073 &nbsp2019</option>
          </select>
        </div>
      
        <div class="pull-right">
          <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#modal_pr" data-toggle="modal" style="width: 100px; color: white"><i class="fa fa-plus"> </i>&nbsp Letter</button>
          @if($counts)
          <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#letter_backdate" data-toggle="modal" style="width: 100px; color: white; margin-right: 10px;"><i class="fa fa-plus"> </i>&nbsp Back Date</button>
          @else
          <button type="button" class="btn btn-success margin-bottom pull-right disabled" id="" data-target="#letter_backdate" data-toggle="modal" style="width: 100px; color: white; margin-right: 10px;" disabled><i class="fa fa-plus"> </i>&nbsp Back Date</button>
          @endif
          <a href="{{url('/downloadExcelLetter')}}"><button class="btn btn-warning" style=" margin-right: 10px;"><i class="fa fa-print"></i> EXCEL </button></a>
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
		                <table class="table table-bordered nowrap table-striped dataTable" id="data_all" width="100%" cellspacing="0">
		                  <thead>
		                    <tr>
		                      <th>No Letter</th>
		                      <th>Position</th>
		                      <th>Type of Letter</th>
		                      <th>Month</th>
		                      <th>Date</th>
		                      <th>To</th>
		                      <th>Attention</th>
		                      <th>Title</th>
		                      <th>Project</th>
		                      <th>Description</th>
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
            </select>
          </div>
          <div class="form-group">
            <label for="">Date</label>
            <input type="date" class="form-control" name="date" id="date" required>
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
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
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
          <div class="form-group">
            <label>Backdate Number</label>
            <select type="text" class="form-control" placeholder="Select Backdate Number" style="width: 100%" name="backdate_num" id="backdate_num" required>
              @foreach($backdate_num as $data)
              <option value="{{$data->no_letter}}">{{$data->no_letter}}</option>
              @endforeach
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
            </select>
          </div>
          <div class="form-group">
            <label for="">Date</label>
            <input type="date" class="form-control" name="date" id="date" required>
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
    function edit_letter(no_letter,to,attention,title,project,description,project_id,note) {
      $('#modaledit').modal('show');
      $('#edit_no_letter').val(no_letter);
      $('#edit_to').val(to);
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
                data.btn_edit = "<button class='btn btn-xs btn-primary' onclick='edit_letter(" + x + ")'>&nbsp Edit</button>";
              } else {
                data.btn_edit = "<button class='btn btn-xs btn-primary disabled'>&nbsp Edit</button>";
              }
                
            });
            return json.data;
            
          }
        },
        "columns": [
          { "data": "no_letter" },
          { "data": "position" },
          { "data": "type_of_letter" },
          { "data": "month" },
          { "data": "date" },
          { "data": "to" },
          { "data": "attention"},
          { "data": "title" },
          { "data": "project" },
          { "data": "description" },
          { "data": "name" },
          { "data": "division" },
          { "data": "project_id" },
          { "data": "note" },
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

    $("#backdate_num").select2();

    function changetabPane(status) {
      $('#data_all').DataTable().ajax.url("{{url('getfilteryearletter')}}?status=" + status + "&year=" + $('#year_filter').val()).load();
    }

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


  </script>
@endsection