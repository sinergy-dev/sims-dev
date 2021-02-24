@extends('template.template_admin-lte')
@section('content')

<style type="text/css">
  th {
    text-align: center;
  }
</style>

<section class="content-header">
  <h1>
    Daftar Buku Admin (PO)
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Admin</li>
    <li class="active">Purchase Order</li>
  </ol>
</section>

<section class="content">
  @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Number PO :<h4> {{$pops->no_po}}</h4></div>
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
                <option value="2020">&#xf073 &nbsp2020</option>
                <option value="2019">&#xf073 &nbsp2019</option>
            </select> -->
            <select style="margin-right: 5px;width: 100px" class="form-control btn-primary fa" id="year_filter">
              <option value="{{$tahun}}">&#xf073 &nbsp{{$tahun}}</option>
              @foreach($year_before as $years)
                @if($years->year != $tahun)
                  <option value="{{$years->year}}">&#xf073 &nbsp{{$years->year}}</option>
                @endif
              @endforeach
            </select>
          </div>
          <div class="pull-right">
          	@if(Auth::User()->name == 'Felicia Debi Noor' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL')
            <button type="button" class="btn btn-success margin-bottom pull-right add-po" id="" style="width: 200px;color: white"><i class="fa fa-plus"> </i>&nbsp Number Purchase Order</button>
            <a href="{{url('/downloadExcelPO')}}"><button class="btn btn-warning" style="margin-right: 10px;"><i class="fa fa-print"></i> Excel </button></a>
            @endif
          </div>
       </div>

       <div class="box-body">
         <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable nowrap" id="data_po" width="100%" cellspacing="0">
              <thead>
                <tr  style="text-align: center;">
                  <th>No PO</th>
                  <th>No. PR</th>
                  <th>Position</th>
                  <th>Type</th>
                  <th>Month</th>
                  <th>Date</th>
                  <th>To</th>
                  <th>Attention</th>
                  <th>Title</th>
                  <th>Project</th>
                  <th>Description</th>
                  <th>From</th>
                  <th>Division</th>
                  <th>Issuance</th>
                  <th>Project ID</th>
                  <th>Note</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="products-list" name="products-list">
                <!-- @foreach($datas as $data)
                <tr>
                  <td>{{$data->no_po}}</td>
                  <td>{{$data->no_pr}}</td>
                  <td>{{$data->position}}</td>
                  <td>{{$data->type_of_letter}}</td>
                  <td>{{$data->month}}</td>
                  <td>{{$data->date}}</td>
                  <td>{{$data->to}}</td>
                  <td>{{$data->attention}}</td>
                  <td>{{$data->title}}</td>
                  <td>{{$data->project}}</td>
                  <td>{{$data->description}}</td>
                  <td>{{$data->name}}</td>
                  <td>{{$data->division}}</td>
                  <td>{{$data->issuance}}</td>
                  <td>{{$data->project_id}}</td>
                  <td>{{$data->note}}</td>
                  <td>
                    @if(Auth::User()->nik == $data->from)
                    <button class="btn btn-xs btn-primary" data-target="#modaledit" data-toggle="modal" style="vertical-align: top; width: 60px" onclick="edit_po('{{$data->no}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}','{{$data->description}}', '{{$data->from}}', '{{$data->issuance}}', '{{$data->project_id}}', '{{$data->division}}')">&nbsp Edit
                    </button>
                    @else
                    <button class="btn btn-xs btn-primary disabled" style="vertical-align: top; width: 60px">&nbsp Edit
                    </button>
                    @endif
                  </td>
                </tr>
                @endforeach -->
              </tbody>
              <tfoot>
              </tfoot>
            </table>
          </div>
       </div>
      </div>

  <!--MODAL ADD PROJECT-->
<div class="modal fade" id="modal_pr" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Number Purchase Order</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_po')}}" id="modal_pr" name="modal_pr">
            @csrf
          <div class="form-group">
          	<label>NO PR</label>
          	<select class="form-control" name="no_pr" id="no_pr" style="width: 100%">
              <!-- <option value="">--Choose No PR--</option>
          		@foreach($no_pr as $data)
          		<option value="{{$data->no}}">{{$data->no_pr}} - {{$data->to}}</option>
          		@endforeach -->
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
            <!-- <select type="text" class="form-control" placeholder="Select Division" name="division" id="division" required>
                <option>PMO</option>
                <option>PRE/Tech/Impl</option>
                <option>MSM</option>
                <option>Marketing</option>
                <option>FA</option>
                <option>HR</option>
            </select> -->
            <input type="text" class="form-control" name="division" id="division" required>
          </div>
          <div class="form-group">
            <label for="">Issuance</label>
            <input type="text" class="form-control" placeholder="Enter Issuance" name="issuance" id="issuance">
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
          <h4 class="modal-title">Edit Purchase Order</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_po')}}" id="modal_edit" name="modal_edit">
            @csrf
          <input type="text" placeholder="Enter No PO" name="edit_no_po" id="edit_no_po" hidden>
          <div class="form-group">
            <label for="">To</label>
            <input type="text" class="form-control" placeholder="Enter To" name="edit_to" id="edit_to" required>
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
          <div class="form-group">
            <label for="">Issuance</label>
            <input type="text" class="form-control" placeholder="Enter Issuance" name="edit_issuance" id="edit_issuance">
          </div>
          <div class="form-group">
            <label for="">Project ID</label>
            <input type="text" class="form-control" placeholder="Enter Project ID" name="edit_project_id" id="edit_project_id">
          </div>
          <!-- <div class="form-group">
            <label for="">Division</label>
            <select type="text" class="form-control" placeholder="Select Division" name="edit_division" id="edit_division" required>
                <option>PMO</option>
                <option>PRE/Tech/Impl</option>
                <option>MSM</option>
                <option>Marketing</option>
                <option>FA</option>
                <option>HR</option>
            </select>
          </div> -->
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

  .DTFC_LeftBodyLiner {
    overflow: hidden;
  }
</style>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/dataTables.fixedColumns.min.js')}}"></script>
  <script type="text/javascript">

    initTablePo();

    function initTablePo() {
      $("#data_po").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('getdatapo')}}",
          "dataSrc": function (json){
            json.data.forEach(function(data,index){
              if("{{Auth::User()->nik}}" == data.from) {
                var x = '"' + data.no + '","' + data.to + '","' + data.attention+ '","' +data.title+ '","' +data.project+ '","' +data.description+ '","' +data.issuance+ '","' +data.project_id+ '","' +data.note+ '"'
                data.btn_edit = "<button class='btn btn-xs btn-primary' onclick='edit_po(" + x + ")'>&nbsp Edit</button>";
              } else {
                data.btn_edit = "<button class='btn btn-xs btn-primary disabled'>&nbsp Edit</button>";
              }                
            });
            return json.data;
          }
        },
        "columns": [
          { "data": "no_po" },
          { "data": "no_pr" },
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
          { "data": "issuance" },
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
            leftColumns: 2
        },
        "pageLength": 20,
      })
    }

    function edit_po(no,to,attention,title,project,description,issuance,project_id,note) {
      $('#modaledit').modal('show');
      $('#edit_no_po').val(no);
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

      if (issuance == "null") {
        '';
      } else {
        $('#edit_issuance').val(issuance);
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

    $('#no_pr').select2();

    $('#no_pr').on('change', function(e){
      var Product = $('#no_pr').val();

         $.ajax({
          type:"GET",
          url:'getDataPrforPo',
          data:{
            data:this.value,
          },
          success: function(result){
            $.each(result[0], function(key, value){
              $("#to").val(value.to);
              $("#attention").val(value.attention);
              $("#title").val(value.title);
              $("#project").val(value.project);
              $("#description").val(value.description);
              $("#division").val(value.division);
              $("#project_id").val(value.project_id);
              $("#issuance").val(value.issuance);
            });
          }
        });
         console.log($('#no_pr').val());
    });

    $(".add-po").click(function(){
      $.ajax({
        type:"GET",
        url:'getPRNumber',
        success: function(result){
          
            $('#no_pr').html(append)
              var append = "<option>-- Select Option --</option>";
              $.each(result[0], function(key, value){
                append = append + "<option value='"+value.no+"'>" + value.no_pr +  "-" + value.to + "</option>";
              });
            $('#no_pr').html(append);
          
        }
      });

      $("#modal_pr").modal("show");
      
    })

    $("#year_filter").change(function(){
      $('#data_po').DataTable().ajax.url("{{url('getfilteryearpo')}}?data=" + this.value).load();
    });

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    }); 
  </script>
@endsection