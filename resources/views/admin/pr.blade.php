@extends('template.template_admin-lte')
@section('content')

<style type="text/css">
  th {
    text-align: center;
  }

  td>.truncate{
    word-break:break-all;
    white-space: normal;
    width:200px;  
  }

</style>

<section class="content-header">
  <h1>
    Daftar Buku Admin (PR)
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Admin</li>
    <li class="active">Purchase Request</li>
  </ol>
</section>

<section class="content">

      @if (session('update'))
        <div class="alert alert-warning" id="alert">
            {{ session('update') }}
        </div>
      @endif

          @if (session('success'))
            <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Number PR :<h4> {{$pops->no_pr}}</h4></div>
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
                  <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#modal_pr" data-toggle="modal" style="width: 200px;color: white"><i class="fa fa-plus"> </i>&nbsp Number Purchase Request</button>
                  <a href="{{url('/downloadExcelPr')}}"><button class="btn btn-warning" style="margin-right: 10px;"><i class="fa fa-print"></i> EXCEL </button></a>
              </div>
           </div>

           <div class="box-body">
            <div class="table-responsive">
                  <table class="table table-bordered table-striped dataTable nowrap" id="data_pr" width="100%" cellspacing="0">
                    <thead>
                      <tr  style="text-align: center;">
                        <th>No</th>
                        <th>Position</th>
                        <th>Type</th>
                        <th>Month</th>
                        <th>Date</th>
                        <th>To</th>
                        <th><div class="truncate">Attention</div></th>
                        <th><div class="truncate">Title/Subject</div></th>
                        <th><div class="truncate">Project</div></th>
                        <th><div class="truncate">Description</div></th>
                        <th>From</th>
                        <th>Division</th>
                        <th>Issuance</th>
                        <th>Project ID</th>
                        <th>Note</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="products-list" name="products-list">
                     <!--  @foreach($datas as $data)
                      <tr>
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
                          <button class="btn btn-xs btn-primary" data-target="#modaledit" data-toggle="modal" style="vertical-align: top; width: 60px" onclick="edit_pr('{{$data->no}}','{{$data->to}}','{{$data->attention}}','{{$data->title}}','{{$data->project}}','{{$data->description}}', '{{$data->from}}', '{{$data->issuance}}', '{{$data->project_id}}', '{{$data->note}}')">&nbsp Edit
                          </button>
                          @else
                          <button class="btn btn-xs btn-primary disabled" style="vertical-align: top; width: 60px">&nbsp Edit
                          </button>
                          @endif
                        </td>
                      </tr>
                      @endforeach -->
                    </tbody>
                  </table>
            </div>
           </div>
          </div>

            <!--MODAL ADD PROJECT-->
    <div class="modal fade" id="modal_pr" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Number Purchase Request</h4>
            </div>
            <div class="modal-body">
              <form method="POST" action="{{url('/store_pr')}}" id="modal_pr" name="modal_pr">
                @csrf
              <div class="form-group">
                <label for="">Position</label>
                <select type="text" class="form-control" placeholder="Select Position" name="position" id="position" required>
                    <option>PMO</option>
                    <option>PRE</option>
                    <option>MSM</option>
                    <option>SAL</option>
                    <option>FIN</option>
                    <option>HRD</option>
                    <option>WHO</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Type of Letter</label>
                <select type="text" class="form-control" placeholder="Select Type of Letter" name="type" id="type" required>
                    <option value="IPR">IPR (Internal Purchase Request)</option>
                    <option value="EPR">EPR (Eksternal Purchase Request)</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Date</label>
                <input type="date" class="form-control" name="date" id="date" required>
              </div>
              <div class="form-group">
                <label for="">To (Customer, DIstributor, Pihak External)</label>
                <input type="text" class="form-control" placeholder="ex. PT. Sinergy Informasi Pratama" name="to" id="to" required>
              </div> 
              <div class="form-group">
                <label for="">Attention/PIC (Customer, DIstributor, Pihak External)</label>
                <input type="text" class="form-control" placeholder="ex. Rama Agastya" name="attention" id="attention" >
              </div> 
              <div class="form-group">
                <label for="">Title/Subject</label>
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
                    <option>FIN</option>
                    <option>HRD</option>
                    <option>WHO</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Issuance</label>
                <input type="text" class="form-control" placeholder="Enter Issuance" name="issuance" id="issuance">
              </div>
              <div class="form-group">
                <label for="">Amount</label>
                <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount">
              </div>
              <div class="form-group">
                <label for="">Project ID</label>                
                <select type="text" class="form-control" placeholder="Select Division" name="project_id" id="project_id" style="width: 100%">
                  <option value="">Select project id</option>
                  @foreach($pid as $data)
                  <option value="{{$data->id_project}}">{{$data->id_project}}</option>
                  @endforeach
                </select>
                <span id="makeId" style="cursor: pointer;">other?</span>
              </div>
              <div class="input-group" style="display: none;" id="project_idNew">
                <input type="text" class="form-control pull-left col-md-8" placeholder="input Project ID" name="project_idInputNew" id="projectIdInputNew">
                <span class="input-group-addon" style="cursor: pointer;" id="removeNewId"><i class="glyphicon glyphicon-remove"></i></span>
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
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Purchase Request</h4>
            </div>
            <div class="modal-body">
              <form method="POST" action="{{url('/update_pr')}}" id="modaledit" name="modaledit">
                @csrf
              <input type="text" placeholder="Enter No PR" name="edit_no_pr" id="edit_no_pr" hidden>
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
              <div class="form-group">
                <label for="">Issuance</label>
                <input type="text" class="form-control" placeholder="Enter Issuance" name="edit_issuance" id="edit_issuance">
              </div>
              <div class="form-group">
                <label for="">Amount</label>
                <input type="text" class="form-control" placeholder="Enter Amount" name="edit_amount" id="edit_amount">
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
  <!-- <script type="text/javascript" src="cdn.datatables.net/fixedcolumns/3.0.0/js/dataTables.fixedColumns.js"></script>
  <script type="text/javascript" src="cdn.datatables.net/fixedcolumns/3.0.0/js/dataTables.fixedColumns.min.js"></script> -->
  <script type="text/javascript">
    $('.money').mask('000,000,000,000,000', {reverse: true});

    $('#makeId').click(function(){
      $('#project_idNew').show()
      $('#project_id').val("").select2().trigger("change")
    })

    $('#removeNewId').click(function(){
      $('#project_idNew').hide('slow')
      $('#projectIdInputNew').val('')
    })
    $('#project_id').select2()
    function edit_pr(no,to,attention,title,project,description,issuance,project_id,note) {
      $('#modaledit').modal('show');
      $('#edit_no_pr').val(no);
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

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    initPrTable();

    function initPrTable() {
      $("#data_pr").DataTable({
        "ajax":{
          "type":"GET",
          "url":"{{url('getdatapr')}}",
          "dataSrc": function (json){

            json.data.forEach(function(data,index){
              if("{{Auth::User()->nik}}" == data.from) {
                var x = '"' + data.no + '","' + data.to + '","' + data.attention+ '","' +data.title+ '","' +data.project+ '","' +data.description+ '","' +data.issuance+ '","' +data.project_id+ '","' +data.note+ '"'
                data.btn_edit = "<button class='btn btn-xs btn-primary' onclick='edit_pr(" + x + ")'>&nbsp Edit</button>";
              } else {
                data.btn_edit = "<button class='btn btn-xs btn-primary disabled'>&nbsp Edit</button>";
              }
                
            });
            return json.data;
            
          }
        },
        "columns": [
          { "data": "no_pr" },
          { "data": "position" },
          { "data": "type_of_letter" },
          { "data": "month" },
          { "data": "date" },
          { "data": "to" },
          {
             "render": function ( data, type, row, meta ) {
                return '<div class="truncate">' + row.attention + '</div>'
              }
          },
          {
             "render": function ( data, type, row, meta ) {
                return '<div class="truncate">' + row.title + '</div>'
              }
          },
          {
             "render": function ( data, type, row, meta ) {
                return '<div class="truncate">' + row.project + '</div>'
              }
          },
          {
             "render": function ( data, type, row, meta ) {
                return '<div class="truncate">' + row.description + '</div>'
              }
          },
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
            leftColumns: 1
        },
        "pageLength": 20,
      })
    }

    $($.fn.dataTable.tables( true ) ).css('width', '100%');
    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    
    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    }); 

    $("#year_filter").change(function(){
      $('#data_pr').DataTable().ajax.url("{{url('getfilteryearpr')}}?data=" + this.value).load();
    });
  </script>
@endsection